<?php

namespace App\Http\Controllers;

use App\Actions\BankAccountTransactionsUploadAction;
use Illuminate\Http\Request;

class BankAccountTransactionController extends Controller
{
    public function upload(Request $request)
    {
        app(BankAccountTransactionsUploadAction::class)->execute($request);
    }
}
