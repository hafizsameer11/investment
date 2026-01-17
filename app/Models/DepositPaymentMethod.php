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
        'minimum_withdrawal_amount',
        'maximum_withdrawal_amount',
        'is_active',
        'allowed_for_deposit',
        'allowed_for_withdrawal',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'allowed_for_deposit' => 'boolean',
        'allowed_for_withdrawal' => 'boolean',
        'minimum_deposit' => 'decimal:2',
        'maximum_deposit' => 'decimal:2',
        'minimum_withdrawal_amount' => 'decimal:2',
        'maximum_withdrawal_amount' => 'decimal:2',
    ];
}
