<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'network',
        'network_display_name',
        'wallet_address',
        'qr_code_image',
        'token',
        'is_active',
        'allowed_for_deposit',
        'allowed_for_withdrawal',
        'minimum_deposit',
        'maximum_deposit',
        'minimum_withdrawal',
        'maximum_withdrawal',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'allowed_for_deposit' => 'boolean',
        'allowed_for_withdrawal' => 'boolean',
        'minimum_deposit' => 'decimal:2',
        'maximum_deposit' => 'decimal:2',
        'minimum_withdrawal' => 'decimal:2',
        'maximum_withdrawal' => 'decimal:2',
    ];

    /**
     * Get all deposits using this crypto wallet
     */
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * Get all withdrawals using this crypto wallet
     */
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }
}
