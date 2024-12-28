<?php

namespace App\Controllers;


use App\Controllers\BaseController;
use App\Models\LoanModel;

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