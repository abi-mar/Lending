<?php
 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use App\Models\UserModel;
 
class RegisterController extends BaseController
{
 
    public function __construct(){
        helper(['form']);
    }
 
    public function index()
    {
        $data = [];
        return view('user/register', $data);
    }
   
    public function register()
    {
        $rules = [
            'username' => ['rules' => 'required|max_length[255]|is_unique[user.username]'],
            'password' => ['rules' => 'required|min_length[4]|max_length[255]'],
            'confirm_password'  => [ 'label' => 'confirm password', 'rules' => 'matches[password]']
        ];
           
 
        if($this->validate($rules)){
            $model = new UserModel();
            $data = [
                'username'    => $this->request->getVar('username'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];
            $model->save($data);
            return redirect()->to('lending/login');
        }else{
            $data['validation'] = $this->validator;
            return view('user/register', $data);
        }
           
    }
}