<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarningCommissionStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'level_name',
        'commission_rate',
        'is_active',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get commission rate for a specific level
     *
     * @param int $level
     * @return float|null
     */
    public static function getCommissionRate($level)
    {
        $global = static::where('level', $level)
            ->where('is_active', true)
            ->first();

        return $global ? $global->commission_rate : null;
    }
}

