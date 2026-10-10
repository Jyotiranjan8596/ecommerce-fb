<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Mail\PendingMobileNumberUpdate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public const PRIME_REFERRAL_THRESHOLD = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'image',
        'name',
        'email',
        'gender',
        'email_verified_at',
        'address',
        'mobilenumber',
        'is_approved',
        'password',
        'role',
        'city',
        'state',
        'country',
        'pan_no',
        'ifsc',
        'account_no',
        'nominee_name',
        'bank',
        'relation_user',
        'zip',
        'sponsor_id',
        'parent_level',
        'active',
        'status'
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
    ];


    public function pos()
    {
        return $this->belongsTo(PosModel::class, 'user_id');
    }
    public function sponcer()
    {
        return $this->hasMany(Sponsor::class, 'user_id');
    }

    public function sponsorUser()
    {
        return $this->belongsTo(self::class, 'sponsor_id', 'id');
    }

    public function referrals()
    {
        return $this->hasMany(self::class, 'sponsor_id', 'id');
    }

    public function getPrimeReferralCountAttribute(): int
    {
        if ((int) $this->role !== 3) {
            return 0;
        }

        if (array_key_exists('referrals_count', $this->attributes)) {
            return (int) $this->attributes['referrals_count'];
        }

        if ($this->relationLoaded('referrals')) {
            return $this->getRelation('referrals')->where('role', 3)->count();
        }

        return $this->referrals()->where('role', 3)->count();
    }

    public function getIsPrimeMemberAttribute(): bool
    {
        return (int) $this->role === 3
            && $this->prime_referral_count >= self::PRIME_REFERRAL_THRESHOLD;
    }

    public function primeMemberSince()
    {
        if (! $this->is_prime_member) {
            return null;
        }

        return $this->referrals()
            ->where('role', 3)
            ->orderBy('created_at')
            ->orderBy('id')
            ->skip(self::PRIME_REFERRAL_THRESHOLD - 1)
            ->value('created_at');
    }

    public function passwordResetOtps()
    {
        return $this->hasMany(OtpVerification::class);
    }

    public static function updatePassword($mob, $pswd, $otp_id)
    {
        self::where('mobilenumber', $mob)
            ->update(['password' => Hash::make($pswd)]);

        OtpVerification::where('id', $otp_id)
            ->update([
                'is_used' => 1,
                'used_at' => now()
            ]);

        return true;
    }

    public function payment(){
        return $this->hasMany(Payment::class,'from','user_id ');
    }
}
