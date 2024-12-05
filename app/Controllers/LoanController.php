<?php

namespace App\Controllers;

class LoanController extends MainController
{
    public function index()
    {
        echo 'Invoking LoanController!';
    }

    public function showAmount($amt)
    {
        echo 'Amount is '.$amt;
    }
}