<?php

use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BankAccountTransactionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (app()->isLocal()) {
        auth()->loginUsingId(1);

        return to_route('dashboard');
    }

    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('/bank-account')->group(function () {
        Route::get('/', [BankAccountController::class, 'index'])->name('bank-account.index');
        Route::post('/', [BankAccountController::class, 'store'])->name('bank-account.store');

        Route::prefix('transactions')->group(function () {
            Route::get('/', function () {
                return view('bankaccount-insert-transaction');
            })->name('bank-account-insert-transaction');

            Route::post('/upload/ofx', [BankAccountTransactionController::class, 'upload'])->name('bank-account-transaction.upload');
            Route::post('/transaction', [BankAccountTransactionController::class, 'store'])->name('bank-account.transaction.store');
        });
    });
});

require __DIR__.'/auth.php';
