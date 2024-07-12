<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccountTransaction extends Model
{
    use HasFactory;

    const CREDIT = 'credit';

    const DEBIT = 'debit';

    protected $fillable = [
        'ofx_type',
        'bank_account_id',
        'cost_center_id',
        'transaction_type',
        'uniqueId',
        'history',
        'memo',
        'amount',
        'date',
    ];

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }
}
