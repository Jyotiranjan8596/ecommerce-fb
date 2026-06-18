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

    // public static function getWalletDetails($date){
    //     $query = Wallet::where('pos_id', $pos->id);
    // }

    public static function getAllTransactions($req)
    {
        try {
            $transQuery = self::with(['userWallets', 'user', 'getPos:id,name'])->orderBy('id', 'desc');

            if ($req->has('from_date') && $req->has('to_date')) {
                $transQuery->whereBetween('transaction_date', [$req->from_date, $req->to_date]);
            } elseif ($req->has('from_date')) {
                $transQuery->where('transaction_date', '>=', $req->from_date);
            } elseif ($req->has('to_date')) {
                $transQuery->where('transaction_date', '<=', $req->to_date);
            }
            return $transQuery->paginate(50)->through(function ($item) {
                $user_wallets = $item->userWallets;
                $user = $item->user;
                foreach ($user_wallets as $user_wallet) {
                    $item->remaining_amount = round($user_wallet->remaining_amount);
                    $item->remaining_reward = round($user_wallet->remaining_points);
                }
                $item->user_name = $user ? $user->name : "NA";
                $item->mobile = $user ? $user->mobilenumber : "NA";
                $item->pos_name = $item->getPos ? $item->getPos->name : "NA";
                $item->trans_date = $item->transaction_date ? date('d-m-Y', strtotime($item->transaction_date)) : 'Na';
                return $item;
            });
        } catch (\Exception $e) {
            Log::info('All transaction Message', ['msg' => $e->getMessage()]);
        }
    }
}
