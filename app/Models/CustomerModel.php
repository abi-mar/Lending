<?php namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model {
    protected $table = 'customer';
    protected $primaryKey = 'custno';
    protected $allowedFields = [
        'firstname', 'middlename', 'surname', 'suffix', 'address', 'mobileno'
    ];

}

?>