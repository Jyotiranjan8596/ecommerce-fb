<?php

namespace App\Models;

use App\Services\WhatsappMessageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class OtpVerification extends Model
{
    use HasFactory;
    protected $table = 'otp_verifications';

    protected $fillable = [
        'user_id',
        'identifier_type', // 1=phone, 2=email, 3=whatsapp
        'identifier',
        'otp',
        'expires_at',
        'is_used',
        'used_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
        'is_used'    => 'boolean',
    ];
    protected $hidden = ['otp'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function storeOtp($otp, $mob)
    {
        try {
            $user_id = User::where('mobilenumber', $mob)->value('id');
            self::create([
                'user_id'         => $user_id,
                'identifier_type' => 1, // phone
                'identifier'      => $mob,
                'otp'             => $otp,
                'expires_at'      => now()->addMinutes(5),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            $whtsapi = WhatsappMessageService::password_reset($mob);
            return true;
        } catch (\Exception $e) {
            Log::info("Store Otp", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function get_otp($mob, $otp)
    {
        return self::where('identifier', $mob)
            ->where('otp', $otp)
            ->where('is_used', 0)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }
}
