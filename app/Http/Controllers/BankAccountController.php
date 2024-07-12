<?php

namespace App\Http\Controllers;

use App\Actions\BankAccountCreateAction;
use App\Models\BankAccount;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index(): View|Factory|Application
    {
        $user = auth()->user();

        $bankAccounts = BankAccount::where('user_id', $user->id)->orderBy('bank_name')->get();

        return view('bankaccount')->with('bankAccounts', $bankAccounts);
    }

    public function store(Request $request)
    {
        try {
            app(BankAccountCreateAction::class)->execute($request);

            return redirect()->route('bank-account.index')
                ->with('success', 'Conta bancária criada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('bank-account.index')
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }

    }
}
