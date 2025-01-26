<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CollectionModel;

class WithdrawController extends BaseController {    

    public function index() {
        $data['pageTitle'] = 'Withdraw';

        $collection = new CollectionModel();

        $collection_data = $collection->first();
        $data['interest'] = $collection_data['interest'];
        $data['LRF'] = $collection_data['LRF'];
        $data['savings'] = $collection_data['savings'];
        $data['damayan'] = $collection_data['damayan'];

        return view('withdraw/index', $data);
    }

    // public function create() {        
    //     $collection = new CollectionModel();

    //     $data['pageTitle'] = 'Withdraw';

    //     return view('withdraw/create', $data);
    // }

    public function withdraw() {
        $data['pageTitle'] = 'Withdraw';

        $collection = new CollectionModel();

        $collection_data = $collection->first();

        $src = $this->request->getPost('source');
        $amt = $this->request->getPost('amount');

        if ($src == 'interest') {
            if ($amt > $collection_data['interest']) {
                return redirect('lending/withdraw')->with('error','Insufficient balance.');
            }

            $update['interest'] = $collection_data['interest'] - $amt;    
            $loan->update(1, $update);
        } else if ($src == 'LRF') {
            if ($amt > $collection_data['LRF']) {
                return redirect('lending/withdraw')->with('error','Insufficient balance.');
            }

            $update['LRF'] = $collection_data['LRF'] - $amt;    
            $loan->update(1, $update);
        } else if ($src == 'savings') {
            if ($amt > $collection_data['savings']) {
                return redirect('lending/withdraw')->with('error','Insufficient balance.');
            }
            
            $update['LRF'] = $collection_data['LRF'] - $amt;    
            $loan->update(1, $update);
        } else if ($src == 'damayan') {
            if ($amt > $collection_data['damayan']) {
                return redirect('lending/withdraw')->with('error','Insufficient balance.');
            }
            
            $update['damayan'] = $collection_data['damayan'] - $amt;    
            $loan->update(1, $update);
        }        

        // display new data
        $collection_data = $collection->first();
        $data['interest'] = $collection_data['interest'];
        $data['LRF'] = $collection_data['LRF'];
        $data['savings'] = $collection_data['savings'];
        $data['damayan'] = $collection_data['damayan'];

        return view('withdraw/index', $data);
    }
}