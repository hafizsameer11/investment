<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositPaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'account_type',
        'account_name',
        'account_number',
        'minimum_deposit',
        'maximum_deposit',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'minimum_deposit' => 'decimal:2',
        'maximum_deposit' => 'decimal:2',
    ];
}
