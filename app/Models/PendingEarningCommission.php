<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingEarningCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'investor_id',
        'investment_id',
        'mining_plan_id',
        'level',
        'earning_amount',
        'commission_rate',
        'commission_amount',
        'is_claimed',
        'claimed_at',
    ];

    protected $casts = [
        'earning_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'is_claimed' => 'boolean',
        'claimed_at' => 'datetime',
    ];

    /**
     * Get the referrer (user who will receive the commission)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the investor (user who made the investment)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function investor()
    {
        return $this->belongsTo(User::class, 'investor_id');
    }

    /**
     * Get the investment that generated this commission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }

    /**
     * Get the mining plan for this commission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function miningPlan()
    {
        return $this->belongsTo(MiningPlan::class);
    }

    /**
     * Scope to get unclaimed commissions
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnclaimed($query)
    {
        return $query->where('is_claimed', false);
    }

    /**
     * Scope to get claimed commissions
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClaimed($query)
    {
        return $query->where('is_claimed', true);
    }

    /**
     * Scope to get commissions for a specific referrer
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $referrerId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForReferrer($query, $referrerId)
    {
        return $query->where('referrer_id', $referrerId);
    }
}

