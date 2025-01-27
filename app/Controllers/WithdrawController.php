<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CollectionModel;
use App\Models\LogsModel;

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
            $collection->update(1, $update);

            // add in logs
            $logs = new LogsModel();

            $logs_data = [                
                'notes' => '[WITHDRAW interest] '.
                        '; [amount] ' . $amt.
                        '; [Old interest balance] ' . $collection_data['interest'].
                        '; [New interest balance] ' . $collection_data['interest'] - $amt,
                'added_by' => session()->get('username')
            ];

            $logs->save($logs_data);
        } else if ($src == 'LRF') {
            if ($amt > $collection_data['LRF']) {
                return redirect('lending/withdraw')->with('error','Insufficient balance.');
            }

            $update['LRF'] = $collection_data['LRF'] - $amt;    
            $collection->update(1, $update);

            // add in logs
            $logs = new LogsModel();

            $logs_data = [                
                'notes' => '[WITHDRAW LRF] '.
                        '; [amount] ' . $amt.
                        '; [Old LRF balance] ' . $collection_data['LRF'].
                        '; [New LRF balance] ' . $collection_data['LRF'] - $amt,
                'added_by' => session()->get('username')
            ];

            $logs->save($logs_data);
        } else if ($src == 'savings') {
            if ($amt > $collection_data['savings']) {
                return redirect('lending/withdraw')->with('error','Insufficient balance.');
            }
            
            $update['savings'] = $collection_data['savings'] - $amt;    
            $collection->update(1, $update);

            // add in logs
            $logs = new LogsModel();

            $logs_data = [                
                'notes' => '[WITHDRAW SAVINGS] '.
                        '; [amount] ' . $amt.
                        '; [Old savings balance] ' . $collection_data['savings'].
                        '; [New savings balance] ' . $collection_data['savings'] - $amt,
                'added_by' => session()->get('username')
            ];

            $logs->save($logs_data);
        } else if ($src == 'damayan') {
            if ($amt > $collection_data['damayan']) {
                return redirect('lending/withdraw')->with('error','Insufficient balance.');
            }
            
            $update['damayan'] = $collection_data['damayan'] - $amt;

            $collection->update(1, $update);

            // add in logs
            $logs = new LogsModel();

            $logs_data = [                
                'notes' => '[WITHDRAW DAMAYAN] '.
                        '; [amount] ' . $amt.
                        '; [Old Damayan balance] ' . $collection_data['damayan'].
                        '; [New Damayan balance] ' . $collection_data['damayan'] - $amt,
                'added_by' => session()->get('username')
            ];

            $logs->save($logs_data);

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