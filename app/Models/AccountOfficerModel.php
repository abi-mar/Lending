<?php namespace App\Models;

use CodeIgniter\Model;

class AccountOfficerModel extends Model{
    protected $table = 'account_officer';
    protected $primaryKey = 'row_id';
    protected $allowedFields = [ 'firstname', 'middlename', 'surname', 'date_added', 'added_by' ];
}