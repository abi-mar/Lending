<?php namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model {
    protected $table = 'payment';
    protected $primaryKey = 'row_id';
    protected $allowedFields = [
        'amount', 'date_paid', 'scheduled_date','added_by', 'is_paid', 'load_record_row_id'
    ];

}

?>