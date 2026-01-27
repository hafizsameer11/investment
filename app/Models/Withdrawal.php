<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'deposit_payment_method_id',
        'crypto_wallet_id',
        'amount',
        'deducted_from_mining',
        'deducted_from_referral',
        'account_holder_name',
        'account_number',
        'bank_name',
        'user_wallet_address',
        'crypto_network',
        'status',
        'admin_notes',
        'admin_proof_image',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'deducted_from_mining' => 'decimal:2',
        'deducted_from_referral' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user who made this withdrawal
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment method used for this withdrawal
     */
    public function paymentMethod()
    {
        return $this->belongsTo(DepositPaymentMethod::class, 'deposit_payment_method_id');
    }

    /**
     * Get the crypto wallet used for this withdrawal (if applicable)
     */
    public function cryptoWallet()
    {
        return $this->belongsTo(CryptoWallet::class);
    }

    /**
     * Get the admin who approved/rejected this withdrawal
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
