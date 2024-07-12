<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends Model
{
    use HasFactory;

    const TYPE_DEFAULT = 'personal';

    const TYPE_PERSONAL = 'personal';

    const TYPE_COMPANY = 'company';

    protected $with = ['bankAccountTransaction', 'bankAccountBalance'];

    protected $fillable = [
        'user_id',
        'type',
        'bank_number',
        'bank_name',
        'bank_branch',
        'bank_account',
        'bank_account_owner_name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bankAccountTransaction(): HasMany
    {
        return $this->hasMany(BankAccountTransaction::class);
    }

    public function bankAccountBalance(): HasMany
    {
        return $this->hasMany(BankAccountBalance::class);
    }
}
