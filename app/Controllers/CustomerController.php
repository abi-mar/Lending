<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\LogsModel;

class CustomerController extends BaseController {
    protected $helpers = ['form'];

    public function index(): string {
        $customer = new CustomerModel();
        $customer->orderBy('balance, date_modified', 'DESC');
        $customer->limit(1000);
        $data['customers'] = $customer->findAll();
        $data['pageTitle'] = 'Customers';

        // reset variable to get total count
        // not sure if reset is necessary
        $customer = new CustomerModel();
        $total_count = $customer->countAll();

        $data['total_count'] = $total_count;

        return view('customer/index.php', $data);
    }

    public function create() {
        return view('customer/create');
    }

    // insert record on customer table
    public function add() {
        $customer = new CustomerModel();

        // server side validation
        // $rules = [
        //     'firstname' => 'required|max_length[100]',
        //     'middlename' => 'max_length[100]',
        //     'surname' => 'required|max_length[100]',
        //     'suffix'    => 'max_length[10]',
        //     'address'    => 'required|max_length[100]',
        //     'mobileno'    => 'required|max_length[20]',
        //     'image'    => 'required'
        // ];

        // $post_data = $this->request->getPost(array_keys($rules));

        // if (! $this->validateData($post_data, $rules)) {
        //     return view('customer/create', ['validation' => $this->validator]);
        // }

        $file = $this->request->getFile('image');
        
        if ($file->isValid() && ! $file->hasMoved()) {             
            $imageName = $file->getRandomName();
            $file->move('uploads/images/', $imageName);

            $data = [
                'firstname' => $this->request->getPost('firstname'),
                'middlename' => $this->request->getPost('middlename'),
                'surname' => $this->request->getPost('surname'),
                'suffix' => $this->request->getPost('suffix'),
                'address' => $this->request->getPost('address'),
                'mobileno' => $this->request->getPost('mobileno'),
                'image' => $imageName,
                'added_by' => session()->get('username')
            ];
    
            $customer->save($data);

            // add in logs
            $logs = new LogsModel();
            
            $logs_data['custno'] = $customer->insertID();

            $logs_data['notes'] = '[CUSTOMER CREATED] [firstname] '. $this->request->getPost('firstname') . 
            '; [middlename] ' . $this->request->getPost('middlename') . 
            '; [surname] ' . $this->request->getPost('surname') . 
            '; [suffix] ' . $this->request->getPost('suffix') . 
            '; [address] ' . $this->request->getPost('address') . 
            '; [mobileno] ' . $this->request->getPost('mobileno');

            $logs_data['added_by'] = session()->get('username');

            $logs->save($logs_data);
            return redirect('lending/customer')->with('status','Customer created successfully!');
        }

    }

    // Redirect to Edit page
    public function edit($custno = null) {
        echo $custno;
        $customer = new CustomerModel();

        $data['customer'] = $customer->find($custno);

        return view('customer/edit.php', $data);
    }

    // update record on customer table
    public function update($custno) {
        $customer = new CustomerModel();

        $file = $this->request->getFile('image');

        $customer_rec = $customer->find($custno);
        $old_img_name = $customer_rec['image'];
        
        if ($file->isValid() && ! $file->hasMoved()) {    // user uploaded a new image          
            $imageName = $file->getRandomName();            
            
            if (file_exists('uploads/images/'.$old_img_name)){
                unlink('uploads/images/'.$old_img_name);
            }

            $file->move('uploads/images/', $imageName);            
        } else {
            $imageName = $old_img_name;
        }

        $data = [
            'firstname' => $this->request->getPost('firstname'),
            'middlename' => $this->request->getPost('middlename'),
            'surname' => $this->request->getPost('surname'),
            'suffix' => $this->request->getPost('suffix'),
            'address' => $this->request->getPost('address'),
            'mobileno' => $this->request->getPost('mobileno'),
            'image' => $imageName
        ];

        $customer->update($custno, $data);

        // add in logs
        $logs = new LogsModel();

        $logs_data['custno'] = $custno;
        $logs_data['notes'] = '[CUSTOMER UPDATED] firstname: '. $this->request->getPost('firstname') .         
        '; [middlename] ' . $this->request->getPost('middlename') . 
        '; [surname] ' . $this->request->getPost('surname') . 
        '; [suffix] ' . $this->request->getPost('suffix') . 
        '; [address] ' . $this->request->getPost('address') . 
        '; [mobileno] ' . $this->request->getPost('mobileno');

        $logs_data['added_by'] = session()->get('username');

        $logs->save($logs_data);

        return redirect('lending/customer')->with('status','Customer updated successfully!');

    }

    // delete record on customer table
    public function delete($custno) {
        $customer = new CustomerModel();

        $customer_rec = $customer->find($custno);
        $img_name = $customer_rec['image'];        
                    
        if (!empty($img_name) && file_exists('uploads/images/'.$img_name)){
            unlink('uploads/images/'.$img_name);
        }

        $customer->delete($custno);

        // add in logs
        $logs = new LogsModel();

        $logs_data = [
            'custno' => $custno,
            'notes' => '[CUSTOMER DELETED]',
            'added_by' => session()->get('username')
        ];

        $logs->save($logs_data);
        return redirect('lending/customer')->with('status','Customer deleted successfully!');

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