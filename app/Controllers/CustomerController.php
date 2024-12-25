<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerModel;

class CustomerController extends BaseController {    

    public function index(): string {
        $customer = new CustomerModel();
        $data['customers'] = $customer->findAll();
        return view('customer/index.php', $data);
    }

    public function create() {
        return view('customer/create');
    }

    // insert record on customer table
    public function add() {
        $customer = new CustomerModel();

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
                'image' => $imageName
            ];
    
            $customer->save($data);
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
        return redirect('lending/customer')->with('status','Customer deleted successfully!');

    }

}