<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Services\WhatsappMessageService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_summury_id',
        'transaction_date',
        'voucher_number',
        'reference_number',
        'account_details',
        'due',
        'amount',
        'to',
        'from',
        'created_by',
        'updated_by',
        'remark',
    ];

    public function creditedTo()
    {
        return $this->belongsTo(User::class, 'to', 'user_id');
    }
    public function creditedFrom()
    {
        return $this->belongsTo(User::class, 'from', 'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function payment_summary()
    {
        return $this->belongsTo(PaymentSummary::class, 'payment_summury_id', 'id');
    }

    public static function create_payment($request)
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $today = now()->toDateString();
            $pos = PosModel::find($request->pos_id);
            if (!$pos) {
                return false;
            }

            $voucherNumber = Helper::generateVoucherNumber();

            if ($request->is_pos) {
                $debitTo  = $pos->user_id;
                $creditTo = 666666;
            } else {
                $debitTo  = 666666;
                $creditTo = $pos->user_id;
            }

            $payment_smry = PaymentSummary::where('date', $request->summary_date)
                ->where('pos_id', $pos->id)
                ->where('status', 'pending')->first();

            $data = [
                [
                    'payment_summury_id' => $payment_smry->id,
                    'transaction_date' => $today,
                    'voucher_number'   => $voucherNumber,
                    'reference_number' => $request->reference_number,
                    'account_details'  => null,
                    'pay_by'           => $request->pay_by,
                    // 'due'              => $request->pay_by ?? 0,
                    'amount'           => $request->paying_amount,
                    'to'               => $creditTo,
                    'from'             => $debitTo,
                    'created_by'       => $user->user_id,
                    'updated_by'       => $user->user_id,
                    'remark'           => $request->remark,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ],
                // [
                //     'payment_summury_id' => $payment_smry->id,
                //     'transaction_date' => $today,
                //     'voucher_number'   => $voucherNumber,
                //     'reference_number' => $request->reference_number,
                //     'account_details'  => null,
                //     'pay_by'           => $request->pay_by,
                //     'due'              => 0,
                //     'debit'            => 0,
                //     'credit'           => $request->paying_amount,
                //     'credited_to'      => $creditTo,
                //     'created_by'       => $user->user_id,
                //     'updated_by'       => $user->user_id,
                //     'remark'           => $request->remark,
                //     'created_at'       => now(),
                //     'updated_at'       => now(),
                // ]
            ];
            if (!$request->is_pos) {
                $update_res = $payment_smry->update([
                    'status' => 'approved',
                    'reference_number' => $request->reference_number
                ]);

                if ($update_res == 0) {
                    DB::rollBack();
                    return false;
                }
            }

            $inserted = Payment::insert($data);

            if (!$inserted) {
                DB::rollBack();
                return false;
            }
            $parameters = [
                'pos_name' => $pos->name,
                'trans_date' => $request->summary_date,
                'settle_date' => $today,
                'amount' => $request->paying_amount
            ];
            WhatsappMessageService::settlement_message($pos->mobilenumber, $parameters);
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Payment creation failed.', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
                'request' => $request->all(),
            ]);

            return false;
        }
    }

    public static function store_receipt($request)
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $today = now()->toDateString();

            $voucherNumber = Helper::generateVoucherNumber();

            $data = [
                [
                    'transaction_date' => $today,
                    'voucher_number'   => $voucherNumber,
                    'reference_number' => $request->reference_number,
                    'account_details'  => null,
                    'pay_by'           => $request->pay_by,
                    // 'due'              => $request->pay_by ?? 0,
                    'amount'           => $request->amount,
                    'to'               => $user->user_id,
                    'from'             => $request->pay_to,
                    'created_by'       => $user->user_id,
                    'updated_by'       => $user->user_id,
                    'remark'           => $request->remark,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]
            ];

            $inserted = Payment::insert($data);

            if (!$inserted) {
                return false;
            }
            // $parameters = [
            //     'pos_name' => $pos->name,
            //     'trans_date' => $request->summary_date,
            //     'settle_date' => $today,
            //     'amount' => $request->paying_amount
            // ];
            // WhatsappMessageService::settlement_message($pos->mobilenumber, $parameters);
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Payment creation failed.', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
                'request' => $request->all(),
            ]);

            return false;
        }
    }


    public static function getLedgerData($request)
    {
        $user_profile = auth()->user();
        // $userId       = $user_profile->user_id;
        $userId = $request->pos_id;
        $opening_balance = 0;
        // $search_type = $request->search_type;
        // $value = $request->value;
        $pos_name = PosModel::where('user_id', $userId)->value('name');
        $from = $request->from_date ?? $request->transaction_date;
        $to = $request->to_date ?? $request->transaction_date;
        $total_debit     = 0;
        $total_credit    = 0;
        $base_query = self::where(function ($base_query) use ($userId) {
            $base_query->where('to', $userId)
                ->orWhere('from', $userId);
        })->with('createdBy')
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc');
        if ($from) {
            $openingTransactions = (clone $base_query)
                ->whereDate('transaction_date', '<', $from)
                ->get();
            foreach ($openingTransactions as $transaction) {
                if ($transaction->from == $userId) {

                    $opening_balance -= $transaction->amount;
                }

                if ($transaction->to == $userId) {

                    $opening_balance += $transaction->amount;
                }
            }
        }
        $query = (clone $base_query)
            ->with('createdBy');

        if ($from && $to) {
            $query->whereBetween(
                'transaction_date',
                [$from, $to]
            )->orderBy('transaction_date', 'asc')
                ->orderBy('id', 'asc');
        }
        $running_balance = $opening_balance;
        $transactions = $query->get()->map(function ($item) use (
            $userId,
            &$running_balance,
            &$total_debit,
            &$total_credit
        ) {
            $item->debit = 0;
            $item->credit = 0;
            $item->date = $item->transaction_date
                ? Carbon::parse($item->transaction_date)->format('d-m-Y')
                : null;
            if ($item->to == $userId) {
                $item->debit = $item->amount;
                $total_debit += $item->amount;
                $item->debit = $item->amount;

                $running_balance += $item->amount;
            }
            if ($item->from == $userId) {
                $item->credit = $item->amount;
                $total_credit += $item->amount;
                $item->credit = $item->amount;

                $running_balance -= $item->amount;
            }
            $item->balance = number_format(abs($running_balance), 2, '.', '');

            $item->balance_type = $running_balance >= 0
                ? 'Dr'
                : 'Cr';
            return $item;
        });
        $total_balance = $opening_balance + $total_debit - $total_credit;
        $total_balance_type = $total_balance >= 0
            ? 'Dr'
            : 'Cr';
        $transactions['opening_balance'] = number_format(abs($opening_balance), 2);
        $transactions['opening_balance_type'] = $opening_balance >= 0 ? 'Dr' : 'Cr';
        $transactions['total_debit'] = number_format(abs($total_debit), 2, '.', '');
        $transactions['total_credit'] = number_format(abs($total_credit), 2, '.', '');
        $transactions['total_balance'] = number_format(abs($total_balance), 2, '.', '');
        $transactions['total_balance_type'] = $total_balance_type;
        $transactions['pos_name'] = $pos_name;
        return $transactions;
    }
    public static function getLedgerDataExport($request)
    {
        $user_profile = auth()->user();
        $userId = $request->pos_id;
        $opening_balance = 0;
        $total_debit     = 0;
        $total_credit    = 0;
        // $search_type = $request->search_type;
        // $value = $request->value;
        $pos_name = PosModel::where('user_id', $userId)->value('name');
        $from = $request->from_date ?? $request->transaction_date;
        $to = $request->to_date ?? $request->transaction_date;
        $base_query = self::where(function ($base_query) use ($userId) {
            $base_query->where('to', $userId)
                ->orWhere('from', $userId);
        })->with('createdBy')
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc');
        if ($from) {
            $openingTransactions = (clone $base_query)
                ->whereDate('transaction_date', '<', $from)
                ->get();
            foreach ($openingTransactions as $transaction) {
                if ($transaction->from == $userId) {

                    $opening_balance -= $transaction->amount;
                }

                if ($transaction->to == $userId) {

                    $opening_balance += $transaction->amount;
                }
            }
        }
        $query = (clone $base_query)
            ->with('createdBy');

        if ($from && $to) {
            $query->whereBetween(
                'transaction_date',
                [$from, $to]
            )->orderBy('transaction_date', 'asc')
                ->orderBy('id', 'asc');
        }
        $running_balance = $opening_balance;
        $transactions = $query->get()->map(function ($item) use (
            $userId,
            &$running_balance,
            &$total_debit,
            &$total_credit
        ) {
            $item->debit = 0;
            $item->credit = 0;
            $item->date = $item->transaction_date
                ? Carbon::parse($item->transaction_date)->format('d-m-Y')
                : null;
            if ($item->to == $userId) {
                $item->debit = $item->amount;
                $total_debit += $item->amount;
                $item->debit = $item->amount;

                $running_balance += $item->amount;
            }
            if ($item->from == $userId) {
                $item->credit = $item->amount;
                $total_credit += $item->amount;
                $item->credit = $item->amount;

                $running_balance -= $item->amount;
            }
            $item->balance = abs($running_balance);

            $item->balance_type = $running_balance >= 0
                ? 'Dr'
                : 'Cr';
            return $item;
        });
        $total_balance = $opening_balance + $total_debit - $total_credit;
        $total_balance_type = $total_balance >= 0
            ? 'Dr'
            : 'Cr';
        return [
            'rows' => $transactions,
            'opening_balance' => abs($opening_balance),
            'opening_balance_type' => $opening_balance >= 0 ? 'Dr' : 'Cr',
            'from' => Helper::formatDate($from),
            'to' => Helper::formatDate($to),
            'name' => $user_profile->name,
            'total_debit' => number_format(abs($total_debit), 2, '.', ''),
            'total_credit' => number_format(abs($total_credit), 2, '.', ''),
            'total_balance' => number_format(abs($total_balance), 2, '.', ''),
            'total_balance_type' => $total_balance_type,
            'pos_name' => $pos_name
        ];
    }

    public static function getReceipt($request)
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->user_id;
        $from = $request->from_date;
        $to = $request->to_date;

        $query = self::where('to', $userId)->with('creditedFrom');
        if ($from && $to) {
            $query->whereBetween(
                'transaction_date',
                [$from, $to]
            );
        }
        $data = $query->get()->map(function ($item) {
            return [
                'voucher' => $item->voucher_number,
                'ref_number' => $item->reference_number,
                'date' => $item->transaction_date,
                'receive_from' => $item->creditedFrom->name,
                'receive_by' => $item->pay_by = 1 ? 'Upi' : 'Cash',
                'amount' => $item->amount,
                'remark' => $item->remark
            ];
        });
        return $data;
    }
}
