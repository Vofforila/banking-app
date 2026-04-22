<?php

namespace App\Http\Controllers;

class TransactionController extends Controller
{
    public function index()
    {
        return view('transactions');
    }

    public function add_transaction()
    {
        return view('add-transaction');
    }
    

}
