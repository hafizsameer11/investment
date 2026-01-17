<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiningPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tagline',
        'subtitle',
        'icon_class',
        'min_investment',
        'max_investment',
        'daily_roi_min',
        'daily_roi_max',
        'hourly_rate',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'min_investment' => 'decimal:2',
        'max_investment' => 'decimal:2',
        'daily_roi_min' => 'decimal:2',
        'daily_roi_max' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get all investments for this mining plan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}







