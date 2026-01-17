<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'role',
        'refer_code',
        'email',
        'phone',
        'password',
        'referred_by',
        'fund_wallet',
        'mining_earning',
        'referral_earning',
        'net_balance',
        'total_invested',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'fund_wallet' => 'decimal:2',
        'mining_earning' => 'decimal:2',
        'referral_earning' => 'decimal:2',
        'net_balance' => 'decimal:2',
        'total_invested' => 'decimal:2',
    ];

    /**
     * Generate a unique referral code from user name
     * Format: FirstNameUppercase + LastNamePascalCase + 4RandomDigits
     * Example: "Ramiz Nazar" -> "RAMEEZNazar2473"
     *
     * @param string $name
     * @return string
     */
    public static function generateReferralCode(string $name): string
    {
        $nameParts = explode(' ', trim($name));
        $firstName = strtoupper($nameParts[0] ?? 'USER');
        $lastName = '';
        
        if (count($nameParts) > 1) {
            $lastName = ucfirst(strtolower($nameParts[count($nameParts) - 1]));
        } else {
            // If no last name, use first 4 chars of first name
            $lastName = substr($firstName, 0, 4);
        }

        // Generate 4 random digits
        $randomDigits = str_pad((string) rand(0, 9999), 4, '0', STR_PAD_LEFT);

        $referralCode = $firstName . $lastName . $randomDigits;

        // Ensure uniqueness
        $attempts = 0;
        while (static::where('refer_code', $referralCode)->exists() && $attempts < 10) {
            $randomDigits = str_pad((string) rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $referralCode = $firstName . $lastName . $randomDigits;
            $attempts++;
        }

        return $referralCode;
    }

    /**
     * Get the user who referred this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by', 'refer_code');
    }

    /**
     * Get users directly referred by this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function directReferrals()
    {
        return $this->hasMany(User::class, 'referred_by', 'refer_code');
    }

    /**
     * Calculate the referral level of a user relative to the current user
     * 
     * @param int $userId The ID of the user to check
     * @param int $maxLevel Maximum level to search (default: 5)
     * @return int|null The level (1-5) or null if not in referral chain
     */
    public function getReferralLevel($userId, $maxLevel = 5)
    {
        if ($userId == $this->id) {
            return 0; // Same user
        }

        $visited = [];
        $queue = [[$this->id, 0]]; // [userId, level]

        while (!empty($queue)) {
            [$currentUserId, $level] = array_shift($queue);

            if ($currentUserId == $userId) {
                return $level;
            }

            if ($level >= $maxLevel || isset($visited[$currentUserId])) {
                continue;
            }

            $visited[$currentUserId] = true;

            // Get direct referrals of current user
            $currentUser = static::find($currentUserId);
            if ($currentUser) {
                $directReferrals = static::where('referred_by', $currentUser->refer_code)->get();
                foreach ($directReferrals as $referral) {
                    if (!isset($visited[$referral->id])) {
                        $queue[] = [$referral->id, $level + 1];
                    }
                }
            }
        }

        return null; // User not found in referral chain
    }

    /**
     * Get all referrals recursively up to N levels deep
     * 
     * @param int $maxLevel Maximum level to traverse (default: 5)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllReferralsRecursive($maxLevel = 5)
    {
        $allReferrals = collect();
        $visited = [];
        $queue = [[$this->id, 0]]; // [userId, level]

        while (!empty($queue)) {
            [$currentUserId, $level] = array_shift($queue);

            if ($level >= $maxLevel || isset($visited[$currentUserId])) {
                continue;
            }

            $visited[$currentUserId] = true;

            // Get direct referrals of current user
            $currentUser = static::find($currentUserId);
            if ($currentUser) {
                $directReferrals = static::where('referred_by', $currentUser->refer_code)->get();
                foreach ($directReferrals as $referral) {
                    if (!isset($visited[$referral->id])) {
                        $referral->referral_level = $level + 1; // Add level as attribute
                        $allReferrals->push($referral);
                        $queue[] = [$referral->id, $level + 1];
                    }
                }
            }
        }

        return $allReferrals;
    }

    /**
     * Get all deposits made by this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * Get all investments made by this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    /**
     * Get all withdrawals made by this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    /**
     * Recalculate and update the net balance
     * Net Balance = Fund Wallet + Mining Earning + Referral Earning
     *
     * @return void
     */
    public function updateNetBalance()
    {
        $this->net_balance = $this->fund_wallet + $this->mining_earning + $this->referral_earning;
        $this->save();
    }

}
