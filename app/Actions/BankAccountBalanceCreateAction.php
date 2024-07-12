<?php

namespace App\Actions;

use App\Models\BankAccount;
use App\Models\BankAccountBalance;
use Illuminate\Http\Request;

class BankAccountBalanceCreateAction
{
    protected function rules(): array
    {
        return [
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'date' => 'required|date_format:Y-m-d',
            'balance' => 'required|string',
        ];
    }

    public function execute(Request $request): void
    {
        $data = $request->validate($this->rules());
        $data['balance'] = formatMoneyToInput($data['balance']);

        //        $bankAccount = BankAccount::findOrfail($request->get('bank_account_id'));
        $balanceExist = BankAccountBalance::where('bank_account_id', $data['bank_account_id'])->where('date', $data['date'])->first();

        if (! $balanceExist) {
            BankAccountBalance::create($data);
        }

    }
}
