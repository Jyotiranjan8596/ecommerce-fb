<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\HasTransactionStatusChanges;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Wallet extends Model
{
    use HasFactory;
    use HasTransactionStatusChanges;

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

    public function scopePendingDsrVerification(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->whereNull('status')->orWhere('status', 0);
        });
    }

    public static function verifyDsr(array $walletIds, int $posId): int
    {
        $wallets = self::query()
            ->whereIn('id', $walletIds)
            ->where('pos_id', $posId)
            ->whereNotNull('transaction_date')
            ->pendingDsrVerification()
            ->get();
        $count = 0;

        foreach ($wallets as $wallet) {
            $wallet->status = 1;
            $wallet->save();
            $count++;
        }

        return $count;
    }

    // public static function getWalletDetails($date){
    //     $query = Wallet::where('pos_id', $pos->id);
    // }

    public static function getAllTransactions($req)
    {
        try {
            $transQuery = self::with(['userWallets', 'user', 'getPos:id,name', 'statusChanges'])->orderBy('id', 'desc');

            if ($req->filled('transaction_date')) {
                $transQuery->whereDate('transaction_date', $req->transaction_date);
            }
            if ($req->filled('user_search')) {
                $search = $req->input('user_search');
                $transQuery->where(function ($query) use ($search) {
                    $query->whereHas('getPos', function ($posQuery) use ($search) {
                        $posQuery->where('user_id', 'LIKE', "%{$search}%")
                            ->orWhere('mobilenumber', 'LIKE', "%{$search}%")
                            ->orWhereHas('user', function ($posUserQuery) use ($search) {
                                $posUserQuery->where('user_id', 'LIKE', "%{$search}%")
                                    ->orWhere('mobilenumber', 'LIKE', "%{$search}%");
                            });
                    })->orWhereHas('user', function ($customerQuery) use ($search) {
                        $customerQuery->where('user_id', 'LIKE', "%{$search}%")
                            ->orWhere('mobilenumber', 'LIKE', "%{$search}%");
                    });
                });
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
                $item->status_label = (int) $item->status === 1 ? 'Verified' : 'Pending';
                $item->status_audit = $item->statusChanges->map(fn (TransactionStatusChange $change) => [
                    'description' => $change->description,
                    'actor' => $change->actor_label,
                    'at' => $change->created_at?->format('d-m-Y h:i A'),
                ])->values();

                return $item;
            });
        } catch (\Exception $e) {
            Log::info('All transaction Message Export', ['msg' => $e->getMessage()]);
        }
    }

    public static function export_user_transaction($req)
    {
        try {
            $transQuery = self::with(['userWallets', 'user', 'getPos:id,name', 'statusChanges'])->orderBy('id', 'desc');

            if ($req->filled('transaction_date')) {
                $transQuery->whereDate('transaction_date', $req->transaction_date);
            }
            if ($req->filled('user_search')) {
                $search = $req->input('user_search');
                $transQuery->where(function ($query) use ($search) {
                    $query->whereHas('getPos', function ($posQuery) use ($search) {
                        $posQuery->where('user_id', 'LIKE', "%{$search}%")
                            ->orWhere('mobilenumber', 'LIKE', "%{$search}%")
                            ->orWhereHas('user', function ($posUserQuery) use ($search) {
                                $posUserQuery->where('user_id', 'LIKE', "%{$search}%")
                                    ->orWhere('mobilenumber', 'LIKE', "%{$search}%");
                            });
                    })->orWhereHas('user', function ($customerQuery) use ($search) {
                        $customerQuery->where('user_id', 'LIKE', "%{$search}%")
                            ->orWhere('mobilenumber', 'LIKE', "%{$search}%");
                    });
                });
            }
            return $transQuery->get()->map(function ($item) {
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
                $item->status_label = (int) $item->status === 1 ? 'Verified' : 'Pending';
                $item->status_actor = $item->statusChanges->first()?->actor_label ?? 'History unavailable';
                return $item;
            });
        } catch (\Exception $e) {
            Log::info('All transaction Message', ['msg' => $e->getMessage()]);
        }
    }

    public static function export_user_transaction_of_pos($date, $id)
    {
        $query = Wallet::where('pos_id', $id)
            ->with('user', 'getPos', 'userWallets', 'statusChanges')
            ->whereDate('transaction_date', $date);
        $wallets = $query->orderBy('id', 'desc')->get()->map(function ($item) {
            return [
                'invoice' => $item?->invoice,
                'pos_id' => $item?->getPos?->user_id ?? 'NA',
                'mobile' => $item?->mobilenumber ?? 'NA',
                'name' => $item?->user?->name ?? 'NA',
                'billing_amount' => $item?->billing_amount ?? 'NA',
                'wallet_deduct' => $item?->amount_wallet ?? 'NA',
                'reward_deduct' => $item?->userWallets[0]?->used_points ?? 'NA',
                'net_pay' => $item?->amount ?? 'NA',
                'remaining_amount' => round($item?->userWallets[0]?->remaining_amount),
                'remaining_reward' => round($item?->userWallets[0]?->remaining_points),
                'transaction_date' => $item?->transaction_date
                    ? date('d-m-Y', strtotime($item->transaction_date))
                    : 'NA',
                'status' => (int) $item->status === 1 ? 'Verified' : 'Unverified',
                'status_actor' => $item->statusChanges->first()?->actor_label ?? 'History unavailable',
            ];
        });
        return $wallets;
    }

    public static function pos_transactions($date, $pos_id)
    {
        $pos_id = PosModel::where('user_id', $pos_id)->value('id');
        // dd($pos_id);
        $wallets =  Wallet::where('pos_id', $pos_id)->whereDate('transaction_date', $date)
            ->with('user', 'userWallets', 'getPos')
            ->orderBy('id', 'desc')
            ->get()->map(function ($item) {
                $chargeRate = (float) ($item->getPos?->transaction_charge ?? 0);
                return [
                    'invoice' => $item->invoice,
                    'mobile' => $item->mobilenumber,
                    'name' => $item->user ? $item->user->name : '',
                    'billing_amount' => $item->billing_amount ?? 0,
                    'wallet_deduct' => $item->amount_wallet ?? 0,
                    'reward_deduct' =>  $item->reward_amount ?? 0,
                    'net_pay' =>  $item->amount ?? 0,
                    'remaining_wallet' =>  $item->userWallets[0]->remaining_amount ?? 0,
                    'remaining_reward' =>  $item->userWallets[0]->remaining_points ?? 0,
                    'transaction_charge' => round((float) ($item->billing_amount ?? 0) * $chargeRate / 100, 2),
                    'transaction_date' =>  date('d/m/Y', strtotime($item->transaction_date)),
                ];
            });
        return $wallets;
    }
}
