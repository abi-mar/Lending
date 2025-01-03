<?php namespace App\Models;

use CodeIgniter\Model;

class LoanModel extends Model {
    protected $table = 'loan_record';
    protected $primaryKey = 'row_id';
    protected $allowedFields = [
        'custno', 'loan_date', 'loan_amount', 'net_proceeds', 'weekly_amortization', 'amount_topay', 'balance','added_by', 
        'service_fee', 'notary', 'doc_stamp', 'interest', 'lrf', 'savings', 'damayan', 'added_by'
    ];

}

?>