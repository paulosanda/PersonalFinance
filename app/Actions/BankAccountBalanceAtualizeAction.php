<?php

namespace App\Actions;

use App\Models\BankAccountBalance;
use App\Models\BankAccountTransaction;

class BankAccountBalanceAtualizeAction
{

    //verificar o balance imediatamente anterior a transaction
    // fazer a soma
    //verificar a diferança entre o saldo resultante e o posterior, se houver diferença fazer a soma

    public function execute(BankAccountTransaction $transaction)
    {
        $imediateLastBalance = BankAccountBalance::where('bank_account_id', $transaction->bank_account_id)
            ->where('date', '<=', $transaction->date)->orderBy('date', 'desc')->get();
        //analisar como fazer esta atualização aqui



    }
}
