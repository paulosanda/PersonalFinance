<?php

namespace App\Actions;


use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BankAccountCreateAction
{
    protected function rules(): array
    {
        return [
            'bank_number' => 'string|required',
            'bank_name' => 'string|required',
            'bank_branch' => 'string|required',
            'bank_account' => 'string|required'
        ];

    }

    /**
     * @throws \Exception
     */
    public function execute(Request $request): void
    {
        $data = $request->validate($this->rules());
        $data['user_id'] = auth()->id();

        try {
            BankAccount::create($data);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

    }
}
