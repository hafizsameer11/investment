<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'level_name',
        'icon_class',
        'icon_color',
        'investment_required',
        'reward_amount',
        'is_premium',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'investment_required' => 'decimal:2',
        'reward_amount' => 'decimal:2',
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get all users who have achieved this reward level
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userRewardLevels()
    {
        return $this->hasMany(UserRewardLevel::class);
    }
}

