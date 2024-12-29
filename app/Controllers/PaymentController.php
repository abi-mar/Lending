<?php

namespace App\Controllers;


use App\Controllers\BaseController;
use App\Models\ScheduledPaymentModel;
use App\Models\PaymentModel;
use App\Models\LoanModel;
use App\Models\CustomerModel;

class PaymentController extends BaseController
{
    public function index(): string {
        $payment = new PaymentModel();
        $data['payments'] = $payment->findAll();
        $data['pageTitle'] = 'Payments';
        return view('payment/index.php', $data);
    }

    public function perLoan($loan_id): string {
        // get weekly amortization
        $loan = new LoanModel();
        $data['loan'] = $loan->find($loan_id);

        $payment = new ScheduledPaymentModel();
        $payment->where('loan_record_row_id', $loan_id);        
        $data['sPayments'] = $payment->find();
        $data['pageTitle'] = 'Scheduled Payments';
        return view('payment/scheduled_payments.php', $data);
    }

    // open payment form
    public function makePayment(): string {
        $customer = new CustomerModel();
        $customer->orderBy('surname', 'ASC');
        $data['customers'] = $customer->findAll();

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

        // GET Loan and Customer records
        $loanRow = $loan->find($loan_id);
        $custRow = $loan->find($loanRow['custno']);
        
        // save payment record
        $data = [
            'loan_record_row_id' => $loan_id,
            'amount' => $amount,
            'payment_date' => $this->request->getPost('payment_date'),
            'added_by' => session()->get('username')
        ];

        $payment->save($data);

        // update loan record balance
        $loan->update($loan_id, ['balance' => $loanRow['balance'] - $amount]);

        /** update customer balance */ 
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
        
        return redirect()->to('lending/payment/perLoan/'.$loan_id)->with('status', 'Payment added successfully!');        
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