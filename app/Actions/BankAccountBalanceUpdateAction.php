<?php

namespace App\Actions;

use App\Models\BankAccountBalance;
use App\Models\BankAccountTransaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BankAccountBalanceUpdateAction
{

    /**
     * @throws Exception
     */
    public function execute(BankAccountTransaction $transaction): void
    {
        DB::beginTransaction();

        try {
            $balance = BankAccountBalance::where('bank_account_id', $transaction->bank_account_id)
                ->where('date', '<=', $transaction->date)->orderBy('date', 'desc')->first();

            if($balance) {
                $balance->update(['balance' => $balance->balance + $transaction->amount]);

            } else {
                $balance = new BankAccountBalance();
                $balance->create([
                    'bank_account_id' => $transaction->bank_account_id,
                    'balance' => $transaction->amount,
                    'date' => $transaction->date,
                ]);
            }

            $this->balancesUpdate($transaction);

            DB::commit();

        } catch (Exception $e) {
            Log::error('bank_account_balance_update failed: ' . $e->getMessage());
            DB::rollBack();
            throw $e;
        }

    }

    public function balancesUpdate(BankAccountTransaction $transaction): void
    {
        $difference = $transaction->amount;

        BankAccountBalance::where('bank_account_id', $transaction->bank_account_id)
            ->where('date', '>', $transaction->date)
            ->get()
            ->each(function ($balance) use ($difference) {
                $balance->balance += $difference;
                $balance->save();
            });
    }
}
