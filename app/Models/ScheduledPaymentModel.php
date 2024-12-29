<?php namespace App\Models;

use CodeIgniter\Model;

class ScheduledPaymentModel extends Model {
    protected $table = 'scheduled_payment';
    protected $primaryKey = 'row_id';
    protected $allowedFields = [
        'amount', 'date_paid', 'scheduled_date','added_by', 'is_paid','remaining_debt','loan_record_row_id'
    ];

}

?>