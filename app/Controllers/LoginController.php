<?php
 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use App\Models\UserModel;
 
class LoginController extends BaseController
{
    public function index()
    {
        return view('user/login');
    } 
   
    public function authenticate()
    {
        $session = session();
        $userModel = new UserModel();
 
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
         
        $user = $userModel->where('username', $username)->first();
 
        if(is_null($user)) {
            return redirect()->back()->withInput()->with('error', 'Invalid username or password1.');
        }
 
        $pwd_verify = password_verify($password, $user['password']);
 
        if(!$pwd_verify) {
            return redirect()->back()->withInput()->with('error', 'Invalid username or password'.$password.' - '.$user['password']);
        }
 
        $ses_data = [
            'id' => $user['id'],
            'username' => $user['username'],
            'isLoggedIn' => TRUE
        ];
 
        $session->set($ses_data);
        return redirect()->to('lending/dashboard');
         
        
    }
 
    public function logout() {
        session_destroy();
        return redirect()->to('lending/login');
        // echo 'logout';
    }
}