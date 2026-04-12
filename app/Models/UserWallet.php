<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'pos_id',
        'wallet_id',
        'invoice',
        'month',
        'used_amount',
        'wallet_amount',
        'remaining_amount',
        'reward_points',
        'used_points',
        'remaining_points',
        'pay_by',
        'trans_type',
        'transaction_date',
        'mobilenumber'
    ];

    public function user_data()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function getPos()
    {
        return $this->belongsTo(PosModel::class, 'pos_id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }

    public static function getAdminWallet($request)
    {
        $year = $request->year;
        $month = $request->month;
        $type = $request->type;
        // dd($year, $month, $type);
        $query = self::whereNotNull('wallet_amount')->with('user_data')->orderBy('id', 'desc');
        if ($month && $year) {
            $formatted = date('M', mktime(0, 0, 0, $month, 1)) . '-' . substr($year, -2);
            // e.g. month=3, year=2026 → "Mar-26"
            $query->where('month', $formatted);
        } elseif ($month) {
            $formatted = date('M', mktime(0, 0, 0, $month, 1));
            $query->where('month', 'LIKE', $formatted . '-%');
            // e.g. month=3 → "Mar-%"
        } elseif ($year) {
            $shortYear = substr($year, -2);
            $query->where('month', 'LIKE', '%-' . $shortYear);
            // e.g. year=2026 → "%-26"
        }

        // Filter by type
        if ($type && $type != 'all') {
            $query->where('trans_type', $type);
        }
        return $query->simplePaginate(15)->through(function ($wallet) {
            // dd($wallet->wallet_amount);
            $wallet->rounded_wallet_amount = ($wallet->wallet_amount - floor($wallet->wallet_amount)) > 0.3
                ? ceil($wallet->wallet_amount)
                : floor($wallet->wallet_amount);
            $wallet->rounded_reward_point = ($wallet->reward_points - floor($wallet->reward_points)) > 0.3
                ? ceil($wallet->reward_points)
                : floor($wallet->reward_points);
            return $wallet;
        });
    }
}
