<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class CustomerController extends MainController
{
    public function index(): string
    {
        $customer = new CustomerModel();
        $data['customers'] = $customer->findAll();
        return view('customer/index.php', $data);
    }
}