<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

it('can create bank account without balance', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $newBankAccount = [
        'bank_name' => fake()->name,
        'bank_number' => fake()->numerify('####'),
        'bank_branch' => fake()->numerify('#####-#'),
        'bank_account' => fake()->numerify('####-##'),

    ];

    $request = $this->post(route('bank-account.store'), $newBankAccount);

    $request->assertRedirect(route('bank-account.index'));

    assertDatabaseCount('bank_accounts', 1);

    assertDatabaseHas('bank_accounts', [
        'bank_name' => $newBankAccount['bank_name'],
        'type' => 'personal',
        'bank_number' => $newBankAccount['bank_number'],
        'bank_branch' => $newBankAccount['bank_branch'],
        'bank_account' => $newBankAccount['bank_account'],
        'bank_account_owner_name' => null,
    ]);

});

it('can create bank account to company', function () {
    $user = User::factory()->create();
    actingAs($user);

    $newBankAccount = [
        'bank_name' => fake()->name,
        'type' => 'company',
        'bank_number' => fake()->numerify('####'),
        'bank_branch' => fake()->numerify('#####-#'),
        'bank_account' => fake()->numerify('####-##'),
    ];

    $request = $this->post(route('bank-account.store'), $newBankAccount);

    $request->assertRedirect(route('bank-account.index'));

    assertDatabaseCount('bank_accounts', 1);

    assertDatabaseHas('bank_accounts', [
        'bank_name' => $newBankAccount['bank_name'],
        'type' => 'company',
        'bank_number' => $newBankAccount['bank_number'],
        'bank_branch' => $newBankAccount['bank_branch'],
        'bank_account' => $newBankAccount['bank_account'],
        'bank_account_owner_name' => null,
    ]);
});

it('can create bank account with bank_account_owner_name without balance', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $newBankAccount = [
        'bank_name' => fake()->name,
        'bank_number' => fake()->numerify('####'),
        'bank_branch' => fake()->numerify('#####-#'),
        'bank_account' => fake()->numerify('####-##'),
        'bank_account_owner_name' => fake()->name,

    ];

    $request = $this->post(route('bank-account.store'), $newBankAccount);

    $request->assertRedirect(route('bank-account.index'));

    assertDatabaseCount('bank_accounts', 1);

    assertDatabaseHas('bank_accounts', [
        'bank_name' => $newBankAccount['bank_name'],
        'bank_number' => $newBankAccount['bank_number'],
        'bank_branch' => $newBankAccount['bank_branch'],
        'bank_account' => $newBankAccount['bank_account'],
        'bank_account_owner_name' => $newBankAccount['bank_account_owner_name'],
    ]);

});

it('can create bank account with bank_account_owner_name with balance', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $newBankAccount = [
        'bank_name' => fake()->name,
        'bank_number' => fake()->numerify('####'),
        'bank_branch' => fake()->numerify('#####-#'),
        'bank_account' => fake()->numerify('####-##'),
        'bank_account_owner_name' => fake()->name,
        'balance' => fake()->numerify('####,##'),
        'date' => fake()->date('Y-m-d'),

    ];

    $request = $this->post(route('bank-account.store'), $newBankAccount);

    $request->assertRedirect(route('bank-account.index'));

    assertDatabaseCount('bank_accounts', 1);

    assertDatabaseCount('bank_account_balances', 1);

    assertDatabaseHas('bank_accounts', [
        'bank_name' => $newBankAccount['bank_name'],
        'bank_number' => $newBankAccount['bank_number'],
        'bank_branch' => $newBankAccount['bank_branch'],
        'bank_account' => $newBankAccount['bank_account'],
        'bank_account_owner_name' => $newBankAccount['bank_account_owner_name'],
    ]);

    assertDatabaseHas('bank_account_balances', [
        'balance' => formatMoneyToInput($newBankAccount['balance']),
    ]);

});
