<?php

namespace App\Models;

use Carbon\Carbon;
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
        'mobilenumber',
        'source',
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
        return self::adminPaybackQuery($request)
            ->paginate(50)
            ->through(fn ($wallet) => self::formatAdminPaybackRow($wallet));
    }

    public static function getAdminWalletExport($request)
    {
        return self::adminPaybackQuery($request)
            ->get()
            ->map(fn ($wallet) => self::formatAdminPaybackRow($wallet));
    }

    private static function adminPaybackQuery($request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $type = $request->input('type', $request->input('trans_type'));
        $payBy = $request->input('pay_by');

        $query = self::whereIn('source', ['admin_import', 'legacy_admin_import'])
            ->whereNotNull('wallet_amount')
            ->whereNotNull('transaction_date')
            ->with('user_data')
            ->orderByDesc('id');

        if (is_numeric($month) && (int) $month >= 1 && (int) $month <= 12) {
            $query->whereMonth('transaction_date', (int) $month);
        }

        if (is_numeric($year)) {
            $query->whereYear('transaction_date', (int) $year);
        }

        if ($type && strtolower(trim($type)) !== 'all') {
            $query->whereRaw('LOWER(TRIM(trans_type)) = ?', [strtolower(trim($type))]);
        }

        if ($payBy === 'wallet') {
            $query->whereRaw('CAST(wallet_amount AS DECIMAL(15, 4)) > 0');
        } elseif ($payBy === 'reward') {
            $query->whereRaw('CAST(reward_points AS DECIMAL(15, 4)) > 0');
        }

        return $query;
    }

    private static function formatAdminPaybackRow(self $wallet): self
    {
        $walletAmount = (float) ($wallet->wallet_amount ?? 0);
        $rewardPoints = (float) ($wallet->reward_points ?? 0);

        $wallet->rounded_wallet_amount = ($walletAmount - floor($walletAmount)) > 0.3
            ? ceil($walletAmount)
            : floor($walletAmount);
        $wallet->rounded_reward_point = ($rewardPoints - floor($rewardPoints)) > 0.3
            ? ceil($rewardPoints)
            : floor($rewardPoints);
        $wallet->month = Carbon::parse($wallet->transaction_date)->format('F,y');

        return $wallet;
    }
}
