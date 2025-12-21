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
}
