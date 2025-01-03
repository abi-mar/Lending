<?php namespace App\Models;

use CodeIgniter\Model;

class CollectionAuditModel extends Model
{
    protected $table = 'collection_audit';
    protected $allowedFields = ['service_fee', 'notary', 'doc_stamp', 'interest', 'LRF', 'savings', 'damayan', 'date_modified', 'modified_by'];
}