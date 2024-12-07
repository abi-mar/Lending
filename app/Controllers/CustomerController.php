<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class CustomerController extends MainController {    

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
        } else {
            return redirect('lending/customer/create')->with('status','Error during customer creation!');
        }       
            
        
       

    }

}