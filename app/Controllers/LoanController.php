<?php

namespace App\Controllers;


use App\Controllers\BaseController;
use App\Models\LoanModel;
use App\Models\CustomerModel;
use App\Models\ScheduledPaymentModel;

class LoanController extends BaseController
{
    public function index(): string {
        $loan = new LoanModel();
        $loan->join('customer', 'customer.custno = loan_record.custno');
        $loan->select('customer.firstname');
        $loan->select('customer.middlename');
        $loan->select('customer.surname');
        $loan->select('loan_record.*');
        $data['loans'] = $loan->findAll();
        $data['pageTitle'] = 'Loans';
        return view('loan/index.php', $data);
    }

    public function create() {
        // get list of customers
        $customer = new CustomerModel();
        $customer->orderBy('surname', 'ASC');
        $data['customers'] = $customer->findAll();
        return view('loan/create', $data);
    }

    // insert record on loan table
    public function add() {        
        $loan = new LoanModel();

        $loan_amount = $this->request->getPost('loan_amount');

        // make constants in future
        $service_fee = 337.50;
        $notary = 50.00;
        $doc_stamp = 50.00;

        // #2
        $total_processing =  $service_fee + $notary + $doc_stamp;

        // #3 NET PROCEEDS = LOAN AMOUNT - #2
        $net_proceeds = $loan_amount - $total_processing;

        // #4
        $interest_rate = 0.045;
        $interest = $loan_amount * $interest_rate * 3;

        $LRF = 400.00; // to be removed

        $savings_rate = 0.10;
        $savings = $loan_amount * $savings_rate;

        $damayan = 400.00;

        // total for #4
        $additional_fees = $interest + $LRF + $savings + $damayan;

        $amount_topay = $loan_amount + $total_processing + $additional_fees;

        $weekly_amortization = $amount_topay / 13; // 13 weeks to pay for now

        $data = [
            'loan_amount' => $loan_amount,
            'custno' => $this->request->getPost('custno'),
            'loan_date' => date('Y-m-d'),
            'weekly_amortization' => $weekly_amortization,
            'net_proceeds' => $net_proceeds,
            'amount_topay' => $amount_topay,
            'balance' => $amount_topay,
            'added_by' => session()->get('username')
        ];    
        
        $loan->save($data);

        $insertID = $loan->insertID();

        // create scheduled payment records
        $scheduled_payments = new ScheduledPaymentModel();

        $weekly_date = '';
        for ($i = 1; $i <= 13; $i++) { // start to pay after 1 week, if NOW $i=0
            $weekly_date = date('Y-m-d', strtotime("+$i week")); 
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

        $customer = new CustomerModel();
        $customer->update($this->request->getPost('custno'), [
            'balance' => $total_balance
        ]);

        return redirect('lending/loan')->with('status','Loan created successfully!');
    }

    // Redirect to Edit page
    // public function edit($custno = null) {
    //     echo $custno;
    //     $loan = new LoanModel();

    //     $data['customer'] = $loan->find($custno);

    //     return view('customer/edit.php', $data);
    // }

    // delete record on customer table
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

        return redirect('lending/loan')->with('status','Loan deleted successfully!');

    }

    // get loan records of a customer: AJAX response
    public function getLoansByCustomer($custno) {
        $loan = new LoanModel();
        $loan->where('custno', $custno);
        $data['loans'] = $loan->findAll();

        return $this->response->setJSON($data);
    }
}