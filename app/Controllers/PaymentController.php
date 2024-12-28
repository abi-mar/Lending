<?php

namespace App\Controllers;


use App\Controllers\BaseController;
use App\Models\PaymentModel;

class PaymentController extends BaseController
{
    public function index(): string {
        $payment = new PaymentModel();
        $data['payments'] = $payment->findAll();
        $data['pageTitle'] = 'Payments';
        return view('payment/index.php', $data);
    }

    public function perLoan($loan_id): string {
        $payment = new PaymentModel();
        $payment->where('load_record_row_id', $loan_id);        
        $data['payments'] = $payment->find();
        $data['pageTitle'] = 'Payments';
        return view('payment/index.php', $data);
    }

    public function create($custno) {
        $data['custno'] = $custno;
        return view('loan/create', $data);
    }

    // insert record on customer table
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

        echo $loan_amount;
        echo $total_processing;
        echo $additional_fees;

        $weekly_amortization = $amount_topay / 13; // 13 weeks to pay for now

        $weekly_dates = [];
        for ($i = 0; $i < 13; $i++) {
            $weekly_dates[] = date('Y-m-d', strtotime("+$i week"));
        }

        $weekly_dates_string = implode(',', $weekly_dates);

        $data = [
            'loan_amount' => $loan_amount,
            'custno' => $this->request->getPost('custno'),
            'loan_date' => date('Y-m-d'),
            'weekly_amortization' => $weekly_amortization,
            'net_proceeds' => $net_proceeds,
            'amount_topay' => $amount_topay,
            'added_by' => session()->get('username')
        ];    
        
        $loan->save($data);

        $insertID = $loan->insertID();

        // create scheduled payment records
        $payment = new PaymentModel();

        $weekly_date = '';
        for ($i = 0; $i < 13; $i++) {
            $weekly_date = date('Y-m-d', strtotime("+$i week"));
            $payment_data = [
                'amount' => 0,
                'date_paid' => NULL,
                'scheduled_date' => $weekly_date,
                'added_by' => NULL,
                'is_paid' => 0,
                'load_record_row_id' => $insertID,
            ];

            $payment->save($payment_data);
        }

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

        $loan_rec = $loan->find($id);

        $loan->delete($id);
        return redirect('lending/loan')->with('status','Loan deleted successfully!');

    }
}