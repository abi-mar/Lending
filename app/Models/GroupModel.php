<?php namespace App\Models;

use CodeIgniter\Model;

class GroupModel extends Model{
    protected $table = 'groupx';
    protected $primaryKey = 'groupno';
    protected $allowedFields = [ 'name', 'date_added', 'added_by' ];
}