<?php namespace App\Models;

use CodeIgniter\Model;

class CollectionModel extends Model
{
    protected $table = 'collection';
    protected $allowedFields = ['service_fee', 'notary', 'doc_stamp', 'interest', 'LRF', 'savings', 'damayan'];
}