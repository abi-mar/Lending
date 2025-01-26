<?php

namespace App\Controllers;


use App\Controllers\BaseController;
use App\Models\LoanModel;
use App\Models\CustomerModel;
use App\Models\ScheduledPaymentModel;
use App\Models\CollectionModel;
use App\Models\LogsModel;

class LoanController extends BaseController
{
    public function index(): string {
        $loan = new LoanModel();
        $loan->join('customer', 'customer.custno = loan_record.custno');
        $loan->join('payment', 'payment.loan_record_row_id = loan_record.row_id', 'left');
        $loan->select('customer.firstname');
        $loan->select('customer.middlename');
        $loan->select('customer.surname');
        $loan->select('loan_record.*');
        $loan->select('IF(payment.row_id IS NOT NULL, "Paid", "Unpaid") AS PaymentStatus');        
        $loan->distinct();
        $loan->orderBy('loan_date', 'DESC');
        $loan->limit(1000);
        $data['loans'] = $loan->findAll();
        $data['pageTitle'] = 'Loans';

        // reset variable to get total count
        // not sure if reset is necessary
        $loan = new LoanModel();
        $total_count = $loan->countAll();

        $data['total_count'] = $total_count;
        return view('loan/index.php', $data);
    }

    public function create() {
        // get list of customers with 0 balance only
        $customer = new CustomerModel();
        $customer->where('balance', 0);
        $customer->orderBy('surname', 'ASC');
        $data['customers'] = $customer->findAll();

        $data['pageTitle'] = 'Loans';
        return view('loan/create', $data);
    }

    // insert record on loan table
    public function add() {        
        $customer = new CustomerModel();

        $customer->where('custno', $this->request->getPost('custno'));
        $customer_data = $customer->first();

        if ($customer_data['balance'] > 0) {
            return redirect('lending/loan')->with('error','Customer has an existing balance. Please settle the balance first.');
        }

        if ($this->request->getPost('loan_amount') < 6000) {
            return redirect('lending/loan/create')->with('error','Loan amount must be at least 6000.');
        }

        $loan = new LoanModel();

        $loan_amount = $this->request->getPost('loan_amount');
        $loan_date = $this->request->getPost('loan_date');

        // make constants in future
        $service_fee = $loan_amount * 0.058; // loan amount x 5.8%
        $notary = 50.00;
        $doc_stamp = 50.00;

        // #2
        $total_processing =  $service_fee + $notary + $doc_stamp;

        // #3 NET PROCEEDS = LOAN AMOUNT - #2
        $net_proceeds = $loan_amount - $total_processing;

        // #4
        $interest_rate = 0.045;
        $interest = $loan_amount * $interest_rate * 3; // 3 months duration

        $LRF = 400.00;

        $savings_rate = 0.10;
        $savings = $loan_amount * $savings_rate;

        $damayan = 400.00;

        // total for #4
        $additional_fees = $interest + $LRF + $savings + $damayan;

        $amount_topay = $loan_amount + $additional_fees;

        $weekly_amortization = $amount_topay / 13; // 13 weeks to pay for now

        $data = [
            'loan_amount' => $loan_amount,
            'custno' => $this->request->getPost('custno'),
            'loan_date' => $loan_date,
            'weekly_amortization' => $weekly_amortization,
            'net_proceeds' => $net_proceeds,
            'amount_topay' => $amount_topay,
            'balance' => $amount_topay,
            'service_fee' => $service_fee,
            'notary' => $notary,
            'doc_stamp' => $doc_stamp,
            'interest' => $interest,
            'lrf' => $LRF,
            'savings' => $savings,
            'damayan' => $damayan,            
            'added_by' => session()->get('username')
        ];
        
        $loan->save($data);

        // add in logs
        $logs = new LogsModel();

        $logs_data = [
            'custno' => $this->request->getPost('custno'),
            'notes' => '[LOAN ADDED] '.
                    '; [loan_amount] ' .           $loan_amount,        
                    '; [loan_date] ' .             $loan_date,
                    '; [weekly_amortization] ' .   $weekly_amortization,
                    '; [net_proceeds] ' .          $net_proceeds,
                    '; [amount_topay] ' .          $amount_topay,
                    '; [balance] ' .               $amount_topay,
                    '; [service_fee] ' .           $service_fee,
                    '; [notary] ' .                $notary,
                    '; [doc_stamp] ' .             $doc_stamp,
                    '; [interest] ' .              $interest,
                    '; [lrf] ' .                   $LRF,
                    '; [savings] ' .               $savings,
                    '; [damayan] ' .               $damayan,
            'added_by' => session()->get('username')
        ];

        $logs->save($logs_data);

        $insertID = $loan->insertID();

        // create scheduled payment records
        $scheduled_payments = new ScheduledPaymentModel();

        $weekly_date = '';
        for ($i = 1; $i <= 13; $i++) { // start to pay after 1 week, if NOW $i=0            
            $weekly_date = date('Y-m-d', strtotime($loan_date . " +$i week"));
            $sPayment_data = [
                'amount' => 0,
                'date_paid' => NULL,
                'scheduled_date' => $weekly_date,
                'added_by' => NULL,
                'is_paid' => 0,
                'remaining_debt' => $weekly_amortization,
                'loan_record_row_id' => $insertID,
            ];

            $scheduled_payments->save($sPayment_data);
        }        

        /** update customer balance */ 
        // get the balance of all loan records of the customer
        $loan->where('custno', $this->request->getPost('custno'));
        $loan_records = $loan->findAll();

        $total_balance = 0;        

        foreach ($loan_records as $row) {
            $total_balance += $row['balance'];
        }

        $customer->update($this->request->getPost('custno'), [
            'balance' => $total_balance
        ]);

        return redirect('lending/loan')->with('status','Loan created successfully!');
    }

    // Redirect to Edit page
    public function edit($loan_id = null) {
        $loan = new LoanModel();
        $data['loan'] = $loan->find($loan_id);

        $data['pageTitle'] = 'Loans';

        return view('loan/edit.php', $data);
    }

    // update record on loan_records table
    public function update($loan_id) {
        $loan = new LoanModel();

        $loan_amount = $this->request->getPost('loan_amount');
        $loan_date = $this->request->getPost('loan_date');
        $custno = $this->request->getPost('custno');

        // get data before updating loan entry
        $old_loan_data = $loan->find($loan_id);

        // make constants in future
        $service_fee = $loan_amount * 0.058; // loan amount x 5.8%
        $notary = 50.00;
        $doc_stamp = 50.00;

        // #2
        $total_processing =  $service_fee + $notary + $doc_stamp;

        // #3 NET PROCEEDS = LOAN AMOUNT - #2
        $net_proceeds = $loan_amount - $total_processing;

        // #4
        $interest_rate = 0.045;
        $interest = $loan_amount * $interest_rate * 3; // 3 months duration

        $LRF = 400.00;

        $savings_rate = 0.10;
        $savings = $loan_amount * $savings_rate;

        $damayan = 400.00;

        // total for #4
        $additional_fees = $interest + $LRF + $savings + $damayan;

        $amount_topay = $loan_amount + $additional_fees;

        $weekly_amortization = $amount_topay / 13; // 13 weeks to pay for now

        $data = [
            'loan_amount' => $loan_amount,            
            'loan_date' => $loan_date,
            'weekly_amortization' => $weekly_amortization,
            'net_proceeds' => $net_proceeds,
            'amount_topay' => $amount_topay,
            'balance' => $amount_topay,
            'service_fee' => $service_fee,
            'notary' => $notary,
            'doc_stamp' => $doc_stamp,
            'interest' => $interest,
            'lrf' => $LRF,
            'savings' => $savings,
            'damayan' => $damayan,
            'modified_by' => session()->get('username')          
        ];

        $loan->update($loan_id, $data);

        
        // add in logs
        $logs = new LogsModel();

        $logs_data = [
            'custno' => $this->request->getPost('custno'),
            'notes' => '[LOAN UPDATED] '.
                    '; [ID] ' .           $loan_id,        
                    '; [loan_amount] ' .           $loan_amount,        
                    '; [loan_date] ' .             $loan_date,
                    '; [weekly_amortization] ' .   $weekly_amortization,
                    '; [net_proceeds] ' .          $net_proceeds,
                    '; [amount_topay] ' .          $amount_topay,
                    '; [balance] ' .               $amount_topay,
                    '; [service_fee] ' .           $service_fee,
                    '; [notary] ' .                $notary,
                    '; [doc_stamp] ' .             $doc_stamp,
                    '; [interest] ' .              $interest,
                    '; [lrf] ' .                   $LRF,
                    '; [savings] ' .               $savings,
                    '; [damayan] ' .               $damayan,
            'added_by' => session()->get('username')
        ];

        $logs->save($logs_data);

        // regenerate customer balance
        $customer = new CustomerModel();

        $loan->where('custno', $custno);
        $loan_records = $loan->findAll();

        $total_balance = 0;

        foreach ($loan_records as $row) {
            $total_balance += $row['balance'];
        }

        $customer->update($custno, [
            'balance' => $total_balance
        ]);

        // update scheduled payments
        $scheduled_payments = new ScheduledPaymentModel();
        $scheduled_payments->where('loan_record_row_id', $loan_id)->delete();

        $weekly_date = '';
        for ($i = 1; $i <= 13; $i++) { // start to pay after 1 week, if NOW $i=0                        
            $weekly_date = date('Y-m-d', strtotime($loan_date . " +$i week"));
            $sPayment_data = [
                'amount' => 0,
                'date_paid' => NULL,
                'scheduled_date' => $weekly_date,
                'added_by' => NULL,
                'is_paid' => 0,
                'remaining_debt' => $weekly_amortization,
                'loan_record_row_id' => $loan_id,
            ];

            $scheduled_payments->save($sPayment_data);
        }
        
        return redirect('lending/loan')->with('status','Loan updated successfully!');

    }

    // delete record on LOAN table
    public function delete($id) {
        $loan = new LoanModel();
        $customer = new CustomerModel();
        
        $loan_rec = $loan->find($id);
        

        // update customer balance
        /** update customer balance */ 
        // get the balance of all loan records of the customer
        $loan->where('custno', $loan_rec['custno']);
        $loan_records = $loan->findAll();

        $total_balance = 0;        

        foreach ($loan_records as $row) {
            $total_balance += $row['balance'];
        }
        
        $customer->update($this->request->getPost('custno'), [
            'balance' => $total_balance
        ]);

        // delete loan record
        $loan->delete($id);

        
        // add in logs
        $logs = new LogsModel();

        $logs_data = [
            'custno' => $this->request->getPost('custno'),
            'notes' => '[LOAN DELETED] Loan ID: '.id,
            'added_by' => session()->get('username')
        ];

        $logs->save($logs_data);

        return redirect('lending/loan')->with('status','Loan deleted successfully!');

    }

    // get loan records of a customer: AJAX response
    public function getLoanByCustomer($custno) {
        $loan = new LoanModel();
        $loan->where('custno', $custno);
        $loan->where('balance >', 0);
        $data = $loan->first();

        return json_encode($data);
    }

    public function getRecordsByBatch($offset): string {
        $loan = new LoanModel();
        $loan->join('customer', 'customer.custno = loan_record.custno');
        $loan->select('customer.firstname');
        $loan->select('customer.middlename');
        $loan->select('customer.surname');
        $loan->select('loan_record.*');
        $loan->orderBy('loan_date', 'DESC');
        $loan->limit(1000, $offset); // page 1 is offset 0, page 2 is offset 1000
        $data = $loan->findAll();
 
        return json_encode($data);
    }
}