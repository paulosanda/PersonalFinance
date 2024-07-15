<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class BankAccountBalance extends Model
{
    use HasFactory;


    protected $fillable = [
        'bank_account_id',
        'balance',
        'date',
    ];

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function isNegative(): bool
    {
        return (float) $this->balance < 0.0;
    }

    public function dateBalance($date)
    {
        return $this->where('date', $date)->first()->balance;
    }

}
