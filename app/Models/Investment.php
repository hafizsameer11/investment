<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mining_plan_id',
        'amount',
        'source_balance',
        'hourly_rate',
        'status',
        'last_profit_calculated_at',
        'total_profit_earned',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'total_profit_earned' => 'decimal:2',
        'last_profit_calculated_at' => 'datetime',
    ];

    /**
     * Get the user who made this investment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the mining plan for this investment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function miningPlan()
    {
        return $this->belongsTo(MiningPlan::class);
    }
}
