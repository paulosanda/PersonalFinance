<?php

namespace App\Actions;

use App\Dtos\BankAccountDto;
use App\Models\BankAccount;
use App\Models\BankAccountTransaction;
use App\Models\CostCenter;
use App\Models\SuspectDpTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OfxParser\Parser as OfxParser;

class BankAccountTransactionsUploadAction
{
    /**
     * @throws \Exception
     */
    public function execute(Request $request): JsonResponse|int
    {
        DB::beginTransaction();

        try {
            $file = $request->file('file_ofx');
            $ofxContent = file_get_contents($file->getPathname());

            $parser = new OfxParser();
            $ofx = $parser->loadFromString($ofxContent);

            $bankAccount = new BankAccountDto(
                user_id: auth()->id(),
                bank_name: (string) $ofx->signOn->institute->name,
                type: null,
                bank_number: (string) $ofx->signOn->institute->id,
                bank_branch: (string) $ofx->bankAccounts[0]->agencyNumber,
                bank_account: (string) $ofx->bankAccounts[0]->accountNumber,
                bank_account_owner_name: null,
                balance: (string) $ofx->bankAccount->balance,
                date: $ofx->bankAccount->balanceDate->format('Y-m-d'),
            );

            $bankAccountId = $this->bankAccountExist($bankAccount);

            $trasanctionsPayload = [];

            foreach ($ofx->bankAccount->statement->transactions as $transaction) {
                $transactionType = $transaction->amount >= 0 ? BankAccountTransaction::CREDIT : BankAccountTransaction::DEBIT;
                $trasanctionsPayload[] = [
                    'ofx_type' => $transaction->type,
                    'bank_account_id' => $bankAccountId,
                    'cost_center_id' => CostCenter::COST_CENTERS_NOT_UNCLASSFIED_ID,
                    'transaction_type' => $transactionType,
                    'uniqueId' => $transaction->uniqueId,
                    'history' => $transaction->memo,
                    'memo' => $transaction->memo,
                    'amount' => $transaction->amount,
                    'date' => $transaction->date->format('Y-m-d'),
                ];
            }

            $transactions = [
                'bank_account_id' => $bankAccountId,
                'start_date' => $ofx->bankAccount->statement->startDate->format('Y-m-d'),
                'end_date' => $ofx->bankAccount->statement->endDate->format('Y-m-d'),
                'transactions' => $trasanctionsPayload,
            ];

            $this->createTransactions($transactions);
            DB::commit();

            return $this->findDuplicates($transactions);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function bankAccountExist(BankAccountDto $bankAccount): int
    {
        $bankAccountExist = BankAccount::where('user_id', $bankAccount->user_id)
            ->whereRaw("REPLACE(LTRIM(REPLACE(bank_number, '-', '')), '0', '') = ?", [str_replace('0', '', str_replace('-', '', ltrim($bankAccount->bank_number, '0')))])
            ->whereRaw("REPLACE(LTRIM(REPLACE(bank_branch, '-', '')), '0', '') = ?", [str_replace('0', '', str_replace('-', '', ltrim($bankAccount->bank_branch, '0')))])
            ->whereRaw("REPLACE(LTRIM(REPLACE(bank_account, '-', '')), '0', '') = ?", [str_replace('0', '', str_replace('-', '', ltrim($bankAccount->bank_account, '0')))])
            ->first();

        if (! $bankAccountExist) {
            $newRequest = new Request($bankAccount->toArray());

            return app(BankAccountCreateAction::class)->execute($newRequest);

        } else {
            return $bankAccountExist->id;
        }
    }

    public function findDuplicates(array $transactions)
    {
        $duplicates = [];

        $seen = [];

        foreach ($transactions['transactions'] as $transaction) {
            $key = $transaction['date'].'|'.$transaction['amount'].'|'.$transaction['memo'].'|'.$transaction['ofx_type'];

            if (isset($seen[$key])) {
                if (! isset($duplicates[$key])) {
                    $duplicates[$key] = [$seen[$key]];
                }
                $duplicates[$key][] = $transaction;
            } else {
                $seen[$key] = $transaction;
            }
        }

        $duplicatesList = [];
        foreach ($duplicates as $dups) {

            $duplicatesList = array_merge($duplicatesList, $dups);
        }

        if (empty($duplicatesList)) {
            return false;

        } else {
            foreach ($duplicatesList as $duplicate) {

                $recordExist = SuspectDpTransaction::where('bank_account_id', $duplicate['bank_account_id'])
                    ->where('uniqueId', $duplicate['uniqueId'])->count();

                if ($recordExist == 0) {
                    $duplicate['status'] = SuspectDpTransaction::PENDING;
                    SuspectDpTransaction::create($duplicate);
                }

            }

            return true;
        }
    }

    private function createTransactions(array $transactions): void
    {
        foreach ($transactions['transactions'] as $transaction) {

            $existingTransaction = BankAccountTransaction::where('uniqueId', $transaction['uniqueId'])
                ->where('bank_account_id', $transaction['bank_account_id'])->count();

            if ($existingTransaction == 0) {

                $transaction = BankAccountTransaction::create($transaction);

                app(BankAccountBalanceUpdateAction::class)->execute($transaction);
            }

        }
    }
}
