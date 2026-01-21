<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRewardLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reward_level_id',
        'achieved_at',
        'reward_amount_credited',
        'is_claimed',
        'claimed_at',
    ];

    protected $casts = [
        'achieved_at' => 'datetime',
        'reward_amount_credited' => 'decimal:2',
        'is_claimed' => 'boolean',
        'claimed_at' => 'datetime',
    ];

    /**
     * Get the user who achieved this reward level
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reward level that was achieved
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rewardLevel()
    {
        return $this->belongsTo(RewardLevel::class);
    }
}
