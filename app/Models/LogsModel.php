<?php namespace App\Models;

use CodeIgniter\Model;

class LogsModel extends Model {
    protected $table = 'logs';
    // protected $primaryKey = 'row_id';
    protected $allowedFields = [
        'custno', 'notes', 'date_added', 'added_by'
    ];

}

?>