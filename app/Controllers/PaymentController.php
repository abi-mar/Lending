<?php

namespace App\Controllers;


use App\Controllers\BaseController;
use App\Models\ScheduledPaymentModel;
use App\Models\PaymentModel;
use App\Models\LoanModel;
use App\Models\CustomerModel;
use App\Models\CollectionModel;
use App\Models\LogsModel;

class PaymentController extends BaseController
{
    public function index(): string {
        $payment = new PaymentModel();
        $payment->select('payment.*, loan_record.loan_date, customer.surname, customer.firstname, customer.middlename');
        $payment->join('loan_record', 'loan_record.row_id = payment.loan_record_row_id');
        $payment->join('customer', 'customer.custno = loan_record.custno');
        $payment->limit(1000);
        $payment->orderBy('payment_date', 'DESC');
        
        $data['payments'] = $payment->findAll();

        // reset $payment variable to get total count
        // not sure if reset is necessary
        $payment = new PaymentModel();
        $payment_total_count = $payment->countAll();

        $data['pageTitle'] = 'Payments';
        $data['total_count'] = $payment_total_count;
        return view('payment/index.php', $data);
    }

    public function perLoan($loan_id): string {
        // get weekly amortization
        $loan = new LoanModel();
        $data['loan'] = $loan->find($loan_id);

        $sPayment = new ScheduledPaymentModel();
        $sPayment->where('loan_record_row_id', $loan_id);        
        $data['sPayments'] = $sPayment->find();
        $data['pageTitle'] = 'Payments';

        $payment = new PaymentModel();
        $payment->where('loan_record_row_id', $loan_id);
        $payment->orderBy('payment_date', 'DESC');
        $data['payments'] = $payment->findAll();
        return view('payment/scheduled_payments.php', $data);
    }

    // open payment form
    public function makePayment(): string {
        $customer = new CustomerModel();
        $customer->select('loan_record.*, customer.surname, customer.firstname, customer.middlename');
        $customer->join('loan_record', 'customer.custno = loan_record.custno');
        $customer->orderBy('surname', 'ASC');        
        $data['customers'] = $customer->findAll();

        $data['pageTitle'] = 'Payments';

        return view('payment/create', $data);
    }

    public function makePaymentPerLoan($loan_record_row_id): string {
        $payment = new PaymentModel();
        $data['loan_record_row_id'] = $loan_record_row_id;

        $loan = new LoanModel();
        $loan->select('loan_record.*, customer.surname, customer.firstname, customer.middlename');
        $loan->join('customer', 'customer.custno = loan_record.custno');
        $loan->where('loan_record.row_id', $loan_record_row_id);
        $data['record'] = $loan->first(); // find seems to return an array object

        $data['pageTitle'] = 'Payments';
        
        return view('payment/create', $data);
    }

    // Add payment record to make payment
    // add validation for when amount is greater than the remaining loan record balance
    public function add() {
        // Models
        $payment = new PaymentModel();        
        $loan = new LoanModel();
        $customer = new CustomerModel();

        $loan_id = $this->request->getPost('loan_record');
        $amount = $this->request->getPost('amount');
        $custno = $this->request->getPost('custno');

        // GET Loan record
        $loanRow = $loan->find($loan_id);

        if($amount > $loanRow['balance']) {
            return redirect()->to('lending/payment/make/'.$loan_id)->with('error', 'Amount is greater than the remaining balance of the loan record!');
        } else if ($amount % $loanRow['weekly_amortization'] != 0) {
            return redirect()->to('lending/payment/make/'.$loan_id)->with('error', 'Amount must be divisible by the weekly amortization!');
        }
        
        // save payment record
        $data = [
            'loan_record_row_id' => $loan_id,
            'amount' => $amount,
            'payment_date' => $this->request->getPost('payment_date'),
            'added_by' => session()->get('username')
        ];

        $payment->save($data);

        // add in logs
        $logs = new LogsModel();

        $logs_data = [
            'custno' => $custno,
            'notes' => '[PAYMENT ADDED]'. 
            ' [loan_record_row_id] '. $loan_id.
            '; [amount] ' . $amount.
            '; [payment_date] '. $this->request->getPost('payment_date'),
            'added_by' => session()->get('username')
        ];

        $logs->save($logs_data);

        // update loan record balance
        $loan->update($loan_id, ['balance' => $loanRow['balance'] - $amount]);

        // /** update customer balance */ 
        // get the balance of all loan records of the customer
        $loan->where('custno', $this->request->getPost('custno'));
        $loan_records = $loan->findAll();

        $total_loan_balance = 0;        

        foreach ($loan_records as $row) {
            $total_loan_balance += $row['balance'];
        }

        $customer->update($loanRow['custno'], ['balance' => $total_loan_balance]);

        // [START] update scheduled payments
        // get weekly amortization
        $weekly_amortization = $loanRow['weekly_amortization'];

        $scheduledPayment = new ScheduledPaymentModel();

        $scheduledPayment->where('loan_record_row_id', $loan_id);
        $scheduledPayment->where('is_paid', '0');
        $scheduledPayment->orderBy('scheduled_date', 'ASC');
        $scheduledPayments = $scheduledPayment->findAll();

        $remainingAmount = $amount;

        foreach ($scheduledPayments as $row) {
            
            if ($remainingAmount >= $row['remaining_debt']) { // fully paid scheduled payment                
                $remainingAmount = $remainingAmount - $weekly_amortization;
                $scheduledPayment->update($row['row_id'], ['is_paid' => 1, 'amount' => $weekly_amortization, 
                'date_paid' => $this->request->getPost('payment_date'), 'paid_by' => session()->get('username'), 'remaining_debt' => 0, 'added_by' => session()->get('username')]);
            } else if ($remainingAmount < $row['remaining_debt'] && $remainingAmount > 0) {
                $debt = $row['remaining_debt'] - $remainingAmount;
                $remainingAmount = 0;
                $scheduledPayment->update($row['row_id'], ['remaining_debt' => $debt]);
            } else {
                break;
            }
        }

        // [END]

        /** Add in collection entry */
        $collection = new CollectionModel();

        $collection_data = $collection->first();

        $update_collection = [
            'interest' => $collection_data['interest'] + ($loanRow['interest'] / 13),
            'savings' => $collection_data['savings'] + ($loanRow['savings'] / 13),
            'LRF' => $collection_data['LRF'] + ($loanRow['lrf'] / 13),
            'damayan' => $collection_data['damayan'] + ($loanRow['damayan'] / 13)
        ];

        $collection->update(1, $update_collection);

        // add in logs
        $logs = new LogsModel();

        $logs_data = [
            'custno' => $custno,
            'notes' => '[COLLECTION ADDED]'. 
            ' [interest] '. $collection_data['interest'] + ($loanRow['interest'] / 13).
            '; [savings] '. $collection_data['savings'] + ($loanRow['savings'] / 13).
            '; [LRF] '. $collection_data['LRF'] + ($loanRow['LRF'] / 13).
            '; [damayan] '. $collection_data['damayan'] + ($loanRow['damayan'] / 13),
            'added_by' => session()->get('username')
        ];

        $logs->save($logs_data);
        
        return redirect()->to('lending/payment/perLoan/'.$loan_id)->with('status', 'Payment added successfully!');        
    }
    
    public function getRecordsByBatch($offset): string {
        $payment = new PaymentModel();
        $payment->select('payment.*, loan_record.loan_date, customer.surname, customer.firstname, customer.middlename');
        $payment->join('loan_record', 'loan_record.row_id = payment.loan_record_row_id');
        $payment->join('customer', 'customer.custno = loan_record.custno');
        $payment->limit(1000, $offset); // page 2 is offset 1000
        $payment->orderBy('payment_date', 'DESC');
        
        $data = $payment->findAll();
        return json_encode($data);
    }

    public function showPendingPayments () {
        $data['pageTitle'] = 'Pending Payments';
        
        return view('payment/pending_payments', $data);
    }

    public function getPendingPaymentsForDay($day): string {
        $dayOfWeek = strtolower($day);
        $daysOfWeek = ['sunday','monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        if (!in_array($dayOfWeek, $daysOfWeek)) {
            return json_encode(['error' => 'Invalid day of the week']);
        }

        $today = new \DateTime();
        $today->setISODate($today->format('o'), $today->format('W'), array_search($dayOfWeek, $daysOfWeek) + 1);
        $date = $today->format('Y-m-d');

        $sPayment = new ScheduledPaymentModel();
        $sPayment->select('customer.surname, customer.firstname, customer.middlename, customer.address, loan_record.weekly_amortization');
        $sPayment->join('loan_record', 'loan_record.row_id = scheduled_payment.loan_record_row_id');
        $sPayment->join('customer', 'customer.custno = loan_record.custno');
        $sPayment->where('scheduled_date', $date);
        $sPayment->limit(1000, 1); // page 2 is offset 1000
        $sPayment->orderBy('customer.surname', 'DESC');
        
        $data = $sPayment->findAll();
        return json_encode($data);
    }

    // Redirect to Edit page
    // public function edit($custno = null) {
    //     echo $custno;
    //     $loan = new LoanModel();

    //     $data['customer'] = $loan->find($custno);

    //     return view('customer/edit.php', $data);
    // }

    // delete record on payment table -- CURRENTLY NOT USED
    // public function delete($id) {
    //     $payment = new PaymentModel();

    //     // $loan_rec = $loan->find($id);

    //     $payment->delete($id);

    //     // add in logs
    //     $logs = new LogsModel();

    //     $logs_data = [
    //         'custno' => $custno,
    //         'notes' => '[PAYMENT DELETED]'. 
    //         ' [ID] '. $id,
    //         'added_by' => session()->get('username')
    //     ];

    //     $logs->save($logs_data);
    //     return redirect('lending/loan')->with('status','Payment deleted successfully!');

    // }
}