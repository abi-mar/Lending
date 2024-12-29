<?php namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model {
    protected $table = 'payment';
    protected $primaryKey = 'row_id';
    protected $allowedFields = [
        'amount', 'payment_date', 'date_added', 'added_by', 'loan_record_row_id'
    ];

}

?>