<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoanModel;
use App\Models\ScheduledPaymentModel;
use App\Models\AccountOfficerModel;
use App\Models\CustomerModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
    /**[START] Collection Per Officer Report */

    public function showCollectionPerOfficer () {
        $data['pageTitle'] = 'Collections';

        $data['accountOfficers'] = (new AccountOfficerModel())->findAll();
        
        return view('report/collectionPerOfficer', $data);
    }

    public function getCollectionPerOfficer($accountOfficersId, $collectionDate): string {
        $scheduledPayment = new ScheduledPaymentModel();

        $scheduledPayment->select('(
            SELECT s2.amount
            FROM scheduled_payment s2
            WHERE s2.row_id < scheduled_payment.row_id AND scheduled_payment.loan_record_row_id = s2.loan_record_row_id        
            ORDER BY s2.row_id
            LIMIT 1
        ) AS previous_amount');

        $scheduledPayment->select('(
            SELECT SUM(s2.remaining_debt)
            FROM scheduled_payment s2
            WHERE s2.row_id < scheduled_payment.row_id AND scheduled_payment.loan_record_row_id = s2.loan_record_row_id        
            ORDER BY s2.row_id        
        ) AS DQ');
        
        $scheduledPayment->select("CONCAT(customer.surname, ', ', customer.firstname, ' ', customer.middlename) as client_name");
        $scheduledPayment->select('customer.custno, loan_record.weekly_amortization, loan_record.savings, loan_record.balance, scheduled_payment.remaining_debt, scheduled_payment.weekno');
        $scheduledPayment->join('loan_record', 'loan_record.row_id = scheduled_payment.loan_record_row_id');
        $scheduledPayment->join('customer', 'customer.custno = loan_record.custno');
        if ($accountOfficersId != 0) {
            $scheduledPayment->where('customer.account_officer_id', $accountOfficersId);
        }
        $scheduledPayment->where('scheduled_payment.scheduled_date', $collectionDate);
        $scheduledPayment->orderBy('customer.surname', 'DESC');

        $data = $scheduledPayment->findAll();
        return json_encode($data);
    }

    public function exportCollectionPerOfficerToExcel() {
        $accountOfficersId = $this->request->getPost('account_officer');
        $collectionDate = $this->request->getPost('collection_date');
        $loan_cycle = $this->request->getPost('loan_cycle');
        $accountOfficerName = $this->request->getPost('account_officer_name');
        $lastWeek = $this->request->getPost('last_week');

        $data = json_decode($this->getCollectionPerOfficer($accountOfficersId, $collectionDate), true);

        $collectionDateStr = date('l, F j, Y', strtotime($collectionDate));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Collection Per Officer');

        // Set light green background color
        // $sheet->getStyle('A1:Z1000')->applyFromArray([
        //     'fill' => [
        //         'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        //         'startColor' => [
        //             'argb' => 'FFCCFFCC',
        //         ],
        //     ],
        // ]);

        // Set Account Officer, Date Collection, Loan Cycle values
        $sheet->setCellValue('B3', 'Account Officer:');
        $sheet->setCellValue('C3', $accountOfficerName);
        $sheet->setCellValue('B4', 'Date Collection:');
        $sheet->setCellValue('C4', $collectionDateStr);
        $sheet->setCellValue('B5', 'Loan Cycle:');
        $sheet->setCellValue('C5', $loan_cycle);

        // Set header to bold
        $sheet->getStyle('B3:B5')->getFont()->setBold(true);

        // Set header
        $sheet->setCellValue('B8', 'Client ID');
        $sheet->setCellValue('C8', 'Client Name');
        $sheet->setCellValue('D8', 'Savings');        
        $sheet->setCellValue('E8', 'Week No');
        $sheet->setCellValue('F8', 'Loan Balance');
        $sheet->setCellValue('G8', 'Delinquent (DQ)');
        $sheet->setCellValue('H8', 'Current');
        $sheet->setCellValue('I8', $lastWeek);        
        $sheet->setCellValue('J8', 'Payment');

        // Set header to bold
        $sheet->getStyle('B8:J8')->getFont()->setBold(true);        

        // Populate data
        $row = 9;
        foreach ($data as $item) {
            $sheet->setCellValue('B' . $row, $item['custno']);            
            $sheet->setCellValue('C' . $row, $item['client_name']);
            $sheet->setCellValue('D' . $row, number_format($item['savings'], 2));
            $sheet->setCellValue('E' . $row, $item['weekno']);
            $sheet->setCellValue('F' . $row, number_format($item['balance'], 2));
            $sheet->setCellValue('G' . $row, number_format($item['DQ'], 2));
            $sheet->setCellValue('H' . $row, number_format($item['remaining_debt'], 2));
            $sheet->setCellValue('I' . $row, number_format($item['previous_amount'], 2));
            $sheet->setCellValue('J' . $row, '');
            
            $row++;
        }

        // Add total row
        $sheet->setCellValue('B' . $row, 'Total');
        $sheet->setCellValue('D' . $row, '=SUMPRODUCT(--SUBSTITUTE(D9:D' . ($row - 1) . ', ",", ""))');
        $sheet->setCellValue('F' . $row, '=SUMPRODUCT(--SUBSTITUTE(F9:F' . ($row - 1) . ', ",", ""))');
        $sheet->setCellValue('G' . $row, '=SUMPRODUCT(--SUBSTITUTE(G9:G' . ($row - 1) . ', ",", ""))');
        $sheet->setCellValue('H' . $row, '=SUMPRODUCT(--SUBSTITUTE(H9:H' . ($row - 1) . ', ",", ""))');
        $sheet->setCellValue('I' . $row, '=SUMPRODUCT(--SUBSTITUTE(I9:I' . ($row - 1) . ', ",", ""))');

        // Format cells as currency
        $sheet->getStyle('D9:D' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('F9:F' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('G9:G' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('H9:H' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('I9:I' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

        // Set cell borders for the whole table
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->getStyle('B8:J' . $row)->applyFromArray($styleArray);

        // Set yellow background color for total row
        $sheet->getStyle('B' . $row . ':J' . $row)->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFFFFF00',
                ],
            ],
        ]);

        // Set total row to bold
        $sheet->getStyle('B' . $row . ':J' . $row)->getFont()->setBold(true);

        // Auto size columns based on cell value
        foreach (range('B', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
            $sheet->getStyle($columnID . '1:' . $columnID . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        }



        $writer = new Xlsx($spreadsheet);        
        $fileName = 'Collection_Per_Officer_' . $collectionDate . '_'. trim($accountOfficerName) . '_' . date('Ymd_His') . '.xlsx';
        $filePath = 'C:/Users/' . getenv('USERNAME') . '/Downloads/' . $fileName;
        $writer->save($filePath);

        return json_encode(['file' => $filePath]);
    }
    /**[END] Collection Per Officer Report */

    /**[START] PENDING PAYMENTS (Currently not used)*/

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
        $customer->select('customer.custno, surname, firstname, middlename, suffix, address, mobileno, loan_record.loan_amount, customer.balance, loan_record.amount_topay');
        $customer->join('loan_record', 'loan_record.custno = customer.custno');
        $customer->where('account_officer_id', $accountOfficersId);
        $customer->where('loan_record.balance >', 0);
        $customer->orderBy('surname', 'DESC');
        $data = $customer->findAll();
        return json_encode($data);
    }
}