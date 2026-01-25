<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'deposit_payment_method_id',
        'crypto_wallet_id',
        'amount',
        'pkr_amount',
        'transaction_id',
        'account_number',
        'account_holder_name',
        'user_wallet_address',
        'crypto_network',
        'payment_proof',
        'status',
        'admin_notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'pkr_amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user who made this deposit
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment method used for this deposit
     */
    public function paymentMethod()
    {
        return $this->belongsTo(DepositPaymentMethod::class, 'deposit_payment_method_id');
    }

    /**
     * Get the crypto wallet used for this deposit (if applicable)
     */
    public function cryptoWallet()
    {
        return $this->belongsTo(CryptoWallet::class);
    }

    /**
     * Get the admin who approved/rejected this deposit
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
