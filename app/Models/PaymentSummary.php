<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Helpers\NumberToWordsHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentSummary extends Model
{
    use HasFactory;
    protected $fillable = [
        'pos_id',
        'date',
        'total_transaction',
        'total_billing_amount',
        'by_cash',
        'by_wallet',
        'by_reward',
        'pos_credit',
        'pos_debit',
        'admin_credit',
        'admin_debit',
        'reference_number',
        'status',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pos_system()
    {
        return $this->belongsTo(PosModel::class, 'pos_id', 'id');
    }
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'payment_summury_id', 'id');
    }

    public static function store_summary($wallet_data)
    {
        // DB::beginTransaction();
        try {
            foreach ($wallet_data as $data) {

                $existing = self::where('date', $data['date'])
                    ->where('pos_id', $data['pos_id'])
                    ->first();

                // Skip if already generated
                if ($existing) {
                    continue;
                }

                $voucherNumber = Helper::generateVoucherNumber();

                $res = self::create([
                    'pos_id'               => $data['pos_id'],
                    'date'                 => $data['date'],
                    'total_transaction'    => $data['total_transactions'],
                    'total_billing_amount' => $data['billing_amount'],
                    'by_cash'              => $data['cash_upi'] ?? $data['payByCashOrUpi'],
                    'by_wallet'            => $data['wallet'] ?? $data['payByWallet'],
                    'by_reward'            => $data['reward'] ?? $data['payByReward'],
                    'pos_credit'           => $data['credit_amount'] ?? $data['creditAmount'],
                    'pos_debit'            => $data['debit_amount'] ?? $data['debitAmount'],

                    // Opposite side for admin
                    'admin_credit'         => $data['debit_amount'] ?? $data['debitAmount'],
                    'admin_debit'          => $data['credit_amount'] ?? $data['creditAmount'],

                    'status'               => 'pending',
                    'created_by'           => null,
                ]);

                if (!$res) {
                    return 3;
                }

                // Determine amount
                $amount = $res->admin_credit > 0
                    ? $res->admin_credit
                    : $res->admin_debit;

                $posUserId = Helper::get_pos_user_id($res->pos_id);

                // Determine transaction direction
                if ($res->pos_credit > 0) {
                    // Admin → POS
                    $from = 666666;
                    $to   = $posUserId;
                } else {
                    // POS → Admin
                    $from = $posUserId;
                    $to   = 666666;
                }

                $payment_data = [
                    'payment_summury_id' => $res->id,
                    'transaction_date'   => $data['date'],
                    'voucher_number'     => $voucherNumber,
                    'reference_number'   => 'atcrt' . $voucherNumber,
                    'account_details'    => null,
                    'pay_by'             => null,
                    'amount'             => $amount,
                    'to'                 => $to,
                    'from'               => $from,
                    'created_by'         => 666666,
                    'updated_by'         => 666666,
                    'remark'              => 'Auto Generated',
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];

                $check = Payment::insert($payment_data);

                if (!$check) {
                    return 3;
                }
            }

            return 2;
        } catch (\Exception $e) {
            Log::info('Summary Creation Error: ' . $e->getMessage());
            Log::info('File: ' . $e->getFile());
            Log::info('Line: ' . $e->getLine());
            return false;
        }
    }

    public static function fetch_summary_pos()
    {
        $user_id = auth()->user()->user_id;
        $pos_id = Helper::get_pos_id($user_id);
        $resp = self::where('pos_id', $pos_id)->orderBy('id', 'desc')->get()->map(function ($item) {
            $item->intiate_date = Carbon::parse($item->date)->format('d-m-Y');
            return $item;
        });
        return $resp;
    }

    public static function fetch_summary_admin()
    {
        $resp = self::with('pos_system')->orderBy('id', 'desc')->get()->map(function ($item) {
            $item->intiate_date = Carbon::parse($item->date)->format('d-m-Y');
            return $item;
        });
        return $resp;
    }

    public static function update_settlement($request)
    {
        // dd($request->all());
        $settlement                   = self::findOrFail($request->id);
        $settlement->reference_number = $request->reference_number;
        $settlement->status           = 'approved';
        $res                          = $settlement->save();
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public static function getinvoice($id)
    {
        $res = self::with('pos_system')->findOrFail($id);

        return [
            'pos_name'             => $res->pos_system->name,
            'pos_user_id'          => $res->pos_system->user_id,
            'bill_date'            => Carbon::parse($res->date)->format('d-m-Y'),
            'total_transaction'    => $res->total_transaction,
            'total_billing_amount' => $res->total_billing_amount,
            'by_cash'              => $res->by_cash,
            'by_wallet'            => $res->by_wallet,
            'by_reward'            => $res->by_reward,
            'credit'         => $res->admin_credit,
            'debit'          => $res->admin_debit,
            'status'               => $res->status,
            'in_letter'            => NumberToWordsHelper::convert($res->admin_debit) . ' Rupees Only/-',
        ];
    }

    public static function getposinvoice($id)
    {
        $res = self::with('creator')->findOrFail($id);

        return [
            'pos_name'             => $res->creator->name,
            'pos_user_id'          => $res->creator->user_id,
            'bill_date'            => Carbon::parse($res->date)->format('d-m-Y'),
            'total_transaction'    => $res->total_transaction,
            'total_billing_amount' => $res->total_billing_amount,
            'by_cash'              => $res->by_cash,
            'by_wallet'            => $res->by_wallet,
            'by_reward'            => $res->by_reward,
            'credit'           => $res->pos_credit,
            'debit'            => $res->pos_debit,
            'status'               => $res->status,
            'in_letter'            => NumberToWordsHelper::convert($res->pos_debit) . ' Rupees Only/-',
        ];
    }

    public static function getWalletDetails($date, $pos_id = null)
    {

        // if ($pos_id) {
        //     $summary = PaymentSummary::where('pos_id', $pos_pid)
        //         ->where('date', $date)
        //         ->where('status', 'approved')
        //         ->get();
        //     if ($summary->isNotEmpty()) {
        //         return false;
        //     }
        // }
        $pos_pid = Helper::get_pos_id($pos_id);
        $data = self::where('pos_id', $pos_pid)->where('date', $date)->with('pos_system', 'payment')->first();
        Log::info('data', ['pos_data' => $data]);
        return $data ?? false;
    }
}
