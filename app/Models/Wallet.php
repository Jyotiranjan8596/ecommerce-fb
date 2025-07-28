<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Wallet extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'description',
        'invoice',
        'billing_amount',
        'amount',
        'pay_by',
        'tran_type',
        'transaction_amount',
        'pay_by_wallet',
        'amount_wallet',
        'reward_amount',
        'user_id',
        'pos_id',
        'insert_by',
        'mobilenumber',
        'transaction_date',
        'status',
        'insert_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function getPos()
    {
        return $this->belongsTo(PosModel::class, 'pos_id', 'id');
    }

    public function userWallets()
    {
        return $this->hasMany(UserWallet::class, 'wallet_id', 'id');
    }

    public static function getWallet($id)
    {
        return self::with('user')->find($id);
    }

    public static function verifyDsr($wallet_ids)
    {
        try {
            $wallets = self::whereIn('id', $wallet_ids)->whereNull('status')->get();
            $count   = 0;

            foreach ($wallets as $wallet) {
                $wallet->status = 1;
                $wallet->save(); // This triggers the updated() observer
                $count++;
            }

            return $count;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return 0;
        }
    }
}
