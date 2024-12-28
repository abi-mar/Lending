<?php
 
namespace App\Controllers;
 
use App\Controllers\BaseController;
 
class DashboardController extends BaseController
{
    public function index()
    {
        $data['pageTitle'] = 'Home';
        return view('dashboard', $data);
    }
}