<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use SebastianBergmann\CodeUnit\FunctionUnit;

class PosModel extends Model
{
    use HasFactory;
    protected $table = 'pos_systems';
    protected $fillable = [
        'user_id',
        'image',
        'name',
        'email',
        'mobilenumber',
        'upi_id',
        'transaction_charge',
        'min_charge',
        'max_charge',
        'initial_letter_of_invoice',
        'pos_code',
        'entity_name',
        'entity_address',
        'entity_contact',
        'comment',
        'address',
        'city',
        'state',
        'zip',
        'latitude',
        'longitude'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function wallet()
    {
        return $this->hasMany(Wallet::class, 'pos_id', 'id');
    }

    public function payment_summary()
    {
        return $this->hasMany(PaymentSummary::class, 'pos_id', 'id');
    }

    public static function getAll()
    {
        return self::orderBy('id', 'desc')->get();
    }

    public static function getPosByUpi($name)
    {
        parse_str(parse_url($name, PHP_URL_QUERY), $params);
        $upi_id = $params['pa'];
        return self::where('upi_id', $upi_id)->first();
    }

    public static function getWalletDetails($date, $pos_id)
    {
        $pos_pid = Helper::get_pos_id($pos_id);
        $summary = PaymentSummary::where('pos_id', $pos_pid)
            ->where('date', $date)
            ->where('status', 'approved')
            ->get();
        if ($summary->isNotEmpty()) {
            return false;
        }
        $query = self::select(['id', 'name', 'transaction_charge'])
            ->with(['wallet' => function ($qry) use ($date) {
                $qry->whereDate('transaction_date', $date);
            }])
            ->whereHas('wallet', function ($qry) use ($date) {
                $qry->whereDate('transaction_date', $date);
            });

        if ($pos_id) {
            $query->where('user_id', $pos_id);
        }

        $data = $query->get()->map(function ($item) use ($date) {
            Log::info('single_data', ['pos_data' => $item]);
            $wallets = $item->wallet;
            $totalTransactions  = $wallets->count();
            $totalBillingAmount = $wallets->sum('billing_amount');
            $payByCashOrUpi     = $wallets->sum('amount');
            $payByWallet        = $wallets->where('pay_by', 'wallet')->sum('amount_wallet');
            $payByReward        = $wallets->where('pay_by', 'reward')->sum('reward_amount');
            $total_reward_wallet_amount = $payByWallet + $payByReward;
            $transaction_amount         = $totalBillingAmount * ($item->transaction_charge / 100);
            if ($total_reward_wallet_amount > $transaction_amount) {
                $creditAmount = $total_reward_wallet_amount - $transaction_amount;
                $debitAmount  = 0;
            } elseif ($total_reward_wallet_amount == 0) {
                $debitAmount  = $transaction_amount;
                $creditAmount = 0;
            } else {
                $debitAmount  = $transaction_amount - $total_reward_wallet_amount;
                $creditAmount = 0;
            }
            $wdata =  [
                'pos_id' => $item->id,
                'pos_name' => $item->name,
                'date' => $date,
                'total_transactions' => $totalTransactions,
                'billing_amount' => $totalBillingAmount,
                'payByCashOrUpi'     => $payByCashOrUpi,
                'payByWallet' => $payByWallet,
                'payByReward' => $payByReward,
                'creditAmount' => $creditAmount, // the credit amount is the debit amount for admin
                'debitAmount' => $debitAmount // the debit amount is the credit amount for admin
            ];
            Log::info('jyoti');
            return $wdata;
        });
        Log::info('data', ['pos_data' => $data]);
        return $data ?? false;
    }
}
