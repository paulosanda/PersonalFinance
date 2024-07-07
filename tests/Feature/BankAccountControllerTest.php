<?php

use App\Models\BankAccount;
use App\Models\User;
use function Pest\Laravel\assertDatabaseCount;

it('can create bank account', function () {
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

    assertDatabaseCount(BankAccount::class, 1);

});

