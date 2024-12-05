<?php

namespace App\Controllers;

class MainController extends BaseController
{
    public function index() : string
    {
        return view('dashboard.php');
        //echo 'invoked MainController index!';
    }
}