<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    use HasFactory;

    const TYPE_PERSONAL = 'personal';

    const TYPE_COMPANY = 'company';

    const TYPE_NEUTRAL = 'neutral';

    const COST_CENTERS_NOT_UNCLASSFIED_ID = 1;

    protected $fillable = [
        'type',
        'const_center_name',
    ];
}
