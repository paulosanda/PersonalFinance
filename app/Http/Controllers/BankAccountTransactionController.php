<?php

namespace App\Http\Controllers;

use App\Actions\BankAccountTransactionsUploadAction;
use Illuminate\Http\Request;

class BankAccountTransactionController extends Controller
{
    public function upload(Request $request)
    {
        $duplicated = app(BankAccountTransactionsUploadAction::class)->execute($request);

        if ($duplicated) {
            return view('bankaccount-insert-transaction')->with(['duplicated' => $duplicated]);
        } else {
            return redirect()->route('bank-account.index');
        }
    }
}
