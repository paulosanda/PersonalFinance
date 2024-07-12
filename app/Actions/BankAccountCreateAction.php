<?php

namespace App\Actions;

use App\Models\BankAccount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BankAccountCreateAction
{
    protected function rules(): array
    {
        return [
            'type' => 'string|nullable',
            'bank_number' => 'string|required',
            'bank_name' => 'string|required',
            'bank_branch' => 'string|required',
            'bank_account' => 'string|required',
            'bank_account_owner_name' => 'string|nullable',
            'balance' => 'string|nullable',
            'date' => 'string|nullable',
        ];

    }

    /**
     * @throws Exception
     */
    public function execute(Request $request): int
    {
        $data = $request->validate($this->rules());
        $data['user_id'] = auth()->id();
        $data['type'] = $data['type'] ?? BankAccount::TYPE_DEFAULT;

        DB::beginTransaction();

        try {
            $newBankAccount = BankAccount::create($data);

            if (isset($data['balance']) && isset($data['date'])) {

                $newRequest = new Request([
                    'bank_account_id' => $newBankAccount->id,
                    'balance' => $data['balance'],
                    'date' => $data['date'],
                ]);

                app(BankAccountBalanceCreateAction::class)->execute($newRequest);
            }

            DB::commit();

            return $newBankAccount->id;

        } catch (Exception $e) {
            Log::error($e->getMessage());

            DB::rollBack();

            throw $e;
        }

    }
}
