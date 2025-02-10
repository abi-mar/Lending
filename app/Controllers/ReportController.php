<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoanModel;
use App\Models\ScheduledPaymentModel;
use App\Models\AccountOfficerModel;
use App\Models\CustomerModel;

class ReportController extends BaseController {    

    public function getSummary() {
        $data['pageTitle'] = 'SummaryDR';
        return view('report/summary', $data);
    }

    public function generateReport(): string {
        $dateFrom = $this->request->getGet('dateFrom');
        $dateTo = $this->request->getGet('dateTo');

        // get total collectibles of all savings, etc from loan records
        $loan = new LoanModel();

        // check if this have return
        $loan->where('loan_date >=', $dateFrom);
        $loan->where('loan_date <=', $dateTo);
        $loan_data = $loan->first();

        if ($loan_data === null) {
            return json_encode(['info' => null]);
        } else {
            // reset
            $loan = new LoanModel();
            $loan->select('SUM(service_fee) as service_fee');
            $loan->select('SUM(notary) as notary');
            $loan->select('SUM(doc_stamp) as doc_stamp');
            $loan->select('SUM(interest) as interest');
            $loan->select('SUM(LRF) as LRF');
            $loan->select('SUM(savings) as savings');
            $loan->select('SUM(damayan) as damayan');
            $loan->select('SUM(amount_topay) as total_collectible'); // get total_collectible = total of all amortization
            $loan->select('SUM(net_proceeds) as capital'); // get capital = total of all net proceeds
            $loan->select('SUM(amount_topay - balance) as collection'); // get collection = amount_topay minus balance (total of amortization paid, collected so far)
            $loan->select('SUM(balance) as current_collectible'); // get current_collectible = total of amortization NOT YET paid
            $loan->where('loan_date >=', $dateFrom);
            $loan->where('loan_date <=', $dateTo);
            $loan_data = $loan->first();

        
            $service_fee = $loan_data['service_fee'];
            $notary = $loan_data['notary'];
            $doc_stamp = $loan_data['doc_stamp'];
            $interest = $loan_data['interest'];
            $LRF = $loan_data['LRF'];
            $savings = $loan_data['savings'];
            $damayan = $loan_data['damayan'];
            $total_collectible = $loan_data['total_collectible'];
            $capital = $loan_data['capital'];
            $collection = $loan_data['collection'];
            $collection_percent = $total_collectible > 0 ? $collection * 100 / $total_collectible : 0;
            $current_collectible = $loan_data['current_collectible'];
            $current_collectible_percent = $total_collectible > 0 ? $current_collectible * 100 / $total_collectible : 0;

            // other computations
            $expected_ROI = $total_collectible - $capital - $savings - $damayan - $LRF;
            $percent_cap = $total_collectible > 0 ? $capital / $total_collectible : 0;
            $percent_ROI = $total_collectible > 0 ? $expected_ROI / $total_collectible : 0;

            $capital_recovered_from_collection = $collection * $percent_cap;
            $capital_to_be_recovered_from_collectible = $current_collectible * $percent_cap;

            $amount_of_roi_from_collection = $collection * $percent_ROI;
            $amount_of_roi_from_collectible = $current_collectible * $percent_ROI;

            $percent_savings = $total_collectible > 0 ? $savings / $total_collectible : 0;
            $percent_damayan = $total_collectible > 0 ? $damayan / $total_collectible : 0;

            $amount_of_savings_from_collection = $collection * $percent_savings;
            $amount_of_savings_from_collectible = $current_collectible * $percent_savings;

            $amount_of_damayan_from_collection = $collection * $percent_damayan;
            $amount_of_damayan_from_collectible = $current_collectible * $percent_damayan;
            
            $data['current_cycle'] = $current_cycle = '1' ? 'First Cycle (January to June)' : 'Second Cycle (July to December)';
            $data['info']['service_fee'] = $service_fee;
            $data['info']['notary'] = $notary;
            $data['info']['doc_stamp'] = $doc_stamp;
            $data['info']['interest'] = $interest;
            $data['info']['LRF'] = $LRF;
            $data['info']['savings'] = $savings;
            $data['info']['damayan'] = $damayan;
            $data['info']['total_collectible'] = $total_collectible;
            $data['info']['capital'] = $capital;
            $data['info']['collection'] = $collection;
            $data['info']['percent of collection'] = $collection_percent;
            $data['info']['current_collectible'] = $current_collectible;
            $data['info']['percent of current_collectible'] = $current_collectible_percent;
            $data['info']['expected_ROI'] = $expected_ROI;
            $data['info']['percent_cap'] = $percent_cap;
            $data['info']['percent_ROI'] = $percent_ROI;
            $data['info']['capital_recovered_from_collection'] = $capital_recovered_from_collection;
            $data['info']['capital_to_be_recovered_from_collectible'] = $capital_to_be_recovered_from_collectible;
            $data['info']['amount_of_ROI_from_collection'] = $amount_of_roi_from_collection;
            $data['info']['amount_of_ROI_from_collectible'] = $amount_of_roi_from_collectible;
            $data['info']['percent_savings'] = $percent_savings;
            $data['info']['percent_damayan'] = $percent_damayan;
            $data['info']['amount_of_savings_from_collection'] = $amount_of_savings_from_collection;
            $data['info']['amount_of_savings_from_collectible'] = $amount_of_savings_from_collectible;
            $data['info']['amount_of_damayan_from_collection'] = $amount_of_damayan_from_collection;
            $data['info']['amount_of_damayan_from_collectible'] = $amount_of_damayan_from_collectible;

            return json_encode($data);
        }
    }

    /**[START] PENDING PAYMENTS */

    public function showPendingPayments () {
        $data['pageTitle'] = 'Pending Payments';
        
        return view('report/pending_payments', $data);
    }

    public function getPendingPaymentsForDay($day): string {
        $dayOfWeek = strtolower($day);
        $daysOfWeek = ['sunday','monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        if ($dayOfWeek < 0 || $dayOfWeek > 7) {
            return json_encode(['error' => 'Invalid day of the week:'.$day]);
        }

        $today = new \DateTime();
        $today->setISODate($today->format('o'), $today->format('W'), $day);
        $date = $today->format('Y-m-d');

        $sPayment = new ScheduledPaymentModel();
        $sPayment->select('groupx.name, customer.surname, customer.firstname, customer.middlename, customer.address, loan_record.weekly_amortization');
        $sPayment->join('loan_record', 'loan_record.row_id = scheduled_payment.loan_record_row_id');
        $sPayment->join('customer', 'customer.custno = loan_record.custno');
        $sPayment->join('groupx', 'customer.groupno = groupx.groupno', 'left');
        $sPayment->where('scheduled_date', $date);
        $sPayment->where('is_paid', 0);
        // $sPayment->limit(1000, 1); // page 2 is offset 1000
        $sPayment->orderBy('customer.surname', 'DESC');
        
        $data = $sPayment->findAll();
        return json_encode($data);
    }

    /**[END] PENDING PAYMENTS */

    
    public function showCustomerPerAO () {
        $data['pageTitle'] = 'Account Officers';

        $accountOfficer = new AccountOfficerModel();

        $data['accountOfficers'] = $accountOfficer->findAll();
        
        return view('report/account_officers', $data);
    }

    public function getCustomersPerAo($accountOfficersId): string {
        $customer = new CustomerModel();
        $customer->where('account_officer_id', $accountOfficersId);
        $customer->orderBy('surname', 'DESC');
        $data = $customer->findAll();
        return json_encode($data);
    }
}