<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentCommissionStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'level_name',
        'commission_rate',
        'is_active',
        'mining_plan_id',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the mining plan for this commission structure
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function miningPlan()
    {
        return $this->belongsTo(MiningPlan::class);
    }

    /**
     * Scope to get global commission structures (no specific plan)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGlobal($query)
    {
        return $query->whereNull('mining_plan_id');
    }

    /**
     * Scope to get commission structures for a specific plan
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $planId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForPlan($query, $planId)
    {
        return $query->where('mining_plan_id', $planId);
    }

    /**
     * Scope to get active commission structures
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get commission rate for a specific level and plan
     * Falls back to global rate if plan-specific rate doesn't exist
     *
     * @param int $level
     * @param int|null $planId
     * @return float|null
     */
    public static function getCommissionRate($level, $planId = null)
    {
        // First try to get plan-specific rate
        if ($planId) {
            $planSpecific = static::where('level', $level)
                ->where('mining_plan_id', $planId)
                ->where('is_active', true)
                ->first();
            
            if ($planSpecific) {
                return $planSpecific->commission_rate;
            }
        }

        // Fall back to global rate
        $global = static::where('level', $level)
            ->whereNull('mining_plan_id')
            ->where('is_active', true)
            ->first();

        return $global ? $global->commission_rate : null;
    }
}
