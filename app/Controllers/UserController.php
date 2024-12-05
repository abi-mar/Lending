<?php

namespace App\Controllers;

class UserController extends MainController
{
    public function login()
    {
        return view('login');
    }
}