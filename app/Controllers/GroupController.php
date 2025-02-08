<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GroupModel;

class GroupController extends BaseController {
    protected $helpers = ['form'];

    public function index(): string {
        $group = new GroupModel();
        $group->orderBy('name', 'ASC');
        $data['groups'] = $group->findAll();
        $data['pageTitle'] = 'Groups';

        return view('group/index.php', $data);
    }

    public function create() {        
        return view('group/create');
    }

    // insert record on customer table
    public function add() {
        $group = new GroupModel();
        
        $data = [
            'name' => $this->request->getPost('name'),            
            'added_by' => session()->get('username')
        ];

        $group->save($data);

        return redirect()->to(base_url('lending/group'))->with('status', 'Group added successfully');
    }
}