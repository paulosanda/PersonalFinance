<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuspectDpTransaction extends Model
{
    use HasFactory;

    const PENDING = 'pending';

    protected $fillable = [
        'bank_account_id',
        'ofx_type',
        'uniqueId',
        'memo',
        'history',
        'amount',
        'date',
        'status',
    ];
}
