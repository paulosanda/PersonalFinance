<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BankAccount extends Model
{
    use HasFactory;

    const TYPE_DEFAULT = 'personal';

    const TYPE_PERSONAL = 'personal';

    const TYPE_COMPANY = 'company';
    const DEFAULT_PERIOD = 5;

    protected $with = ['latestTransactions', 'latestBalance'];

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

    public function transaction(): HasMany
    {
        return $this->hasMany(BankAccountTransaction::class);
    }

    public function balance(): HasMany
    {
        return $this->hasMany(BankAccountBalance::class);
    }

    public function latestBalance(): HasOne
    {
        return $this->hasOne(BankAccountBalance::class)->latest('date');
    }

    public function latestTransactions(int $days = self::DEFAULT_PERIOD): HasMany
    {
        $dateLimit = now()->subDays($days);

        return $this->hasMany(BankAccountTransaction::class)
            ->where('date', '<=', $dateLimit)
            ->orderBy('date', 'desc');
    }

}
