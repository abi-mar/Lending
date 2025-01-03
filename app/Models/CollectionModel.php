use CodeIgniter\Model;

<?php

use CodeIgniter\Model;

class CollectionModel extends Model
{
    protected $table = 'collection';
    protected $allowedFields = ['service_fee', 'notary', 'doc_stamp', 'interest', 'LRF', 'savings', 'damayan'];
}