<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Services\WhatsappMessageService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
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
        'status'
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
            $isPos = $request->boolean('is_pos');
            $pos = PosModel::find($request->pos_id);
            if (!$pos) {
                DB::rollBack();
                return false;
            }

            $voucherNumber = Helper::generateVoucherNumber();

            if ($isPos) {
                $debitTo  = $pos->user_id;
                $creditTo = 666666;
            } else {
                $debitTo  = 666666;
                $creditTo = $pos->user_id;
            }

            $payment_smry = PaymentSummary::where('date', $request->summary_date)
                ->where('pos_id', $pos->id)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->first();
            if (!$payment_smry) {
                DB::rollBack();
                return false;
            }

            $generatedEntry = $payment_smry->ensureGeneratedPaymentEntry();
            $referenceNumber = $generatedEntry?->reference_number;

            if (!$referenceNumber) {
                DB::rollBack();
                return false;
            }

            $settlementAmount = $isPos ? $payment_smry->admin_credit : $payment_smry->admin_debit;
            $remainingCents = self::remainingSettlementCents(
                $payment_smry,
                $debitTo,
                $creditTo,
                $settlementAmount
            );
            $payingCents = (int) round((float) $request->paying_amount * 100);
            if ($payingCents <= 0 || $payingCents > $remainingCents) {
                DB::rollBack();
                return false;
            }
            $remainingAfterPayment = $remainingCents - $payingCents;

            $data = [
                [
                    'payment_summury_id' => $payment_smry->id,
                    'transaction_date' => $today,
                    'voucher_number'   => $voucherNumber,
                    'reference_number' => $referenceNumber,
                    'account_details'  => null,
                    'pay_by'           => $request->pay_by,
                    'due'              => number_format($remainingAfterPayment / 100, 2, '.', ''),
                    'amount'           => $request->paying_amount,
                    'to'               => $creditTo,
                    'from'             => $debitTo,
                    'created_by'       => $user->user_id,
                    'updated_by'       => $user->user_id,
                    'remark'           => $request->remark,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]
            ];
            $inserted = Payment::insert($data);

            if (!$inserted) {
                DB::rollBack();
                return false;
            }
            if (!$isPos && !$payment_smry->reference_number) {
                $payment_smry->reference_number = $referenceNumber;
                $payment_smry->save();
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

    private static function remainingSettlementCents(
        PaymentSummary $summary,
        string $from,
        string $to,
        int|float|string|null $settlementAmount
    ): int {
        $paidAmount = self::query()
            ->where('payment_summury_id', $summary->id)
            ->where('from', $from)
            ->where('to', $to)
            ->where(function ($query) {
                $query->whereNull('remark')
                    ->orWhere('remark', '!=', 'Auto Generated');
            })
            ->sum('amount');

        return max(
            0,
            (int) round((float) $settlementAmount * 100) - (int) round((float) $paidAmount * 100)
        );
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
                    'payment_summury_id' => null,
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
                DB::rollBack();
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


    public static function getLedgerData($request, ?string $accountUserId = null, bool $adminView = false)
    {
        $user_profile = auth()->user();
        $userId       = $accountUserId ?? $user_profile->user_id;
        $opening_balance = 0;
        // $search_type = $request->search_type;
        // $value = $request->value;
        $from = $request->from_date;
        $to = $request->to_date;
        $total_debit     = 0;
        $total_credit    = 0;
        $base_query = self::ledgerQueryForAccount($userId)
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc');
        if ($from) {
            $openingTransactions = (clone $base_query)
                ->whereDate('transaction_date', '<', $from)
                ->get();
            foreach ($openingTransactions as $transaction) {
                $opening_balance += self::ledgerBalanceDelta($transaction, $userId, $adminView);
            }
        }
        $query = (clone $base_query)
            ->with(['creditedTo', 'creditedFrom']);

        if ($from && $to) {
            $query->whereBetween('transaction_date', [$from, $to]);
        } elseif ($from) {
            $query->whereDate('transaction_date', '>=', $from);
        } elseif ($to) {
            $query->whereDate('transaction_date', '<=', $to);
        }
        $running_balance = $opening_balance;
        $transactions = $query->get()->map(function ($item) use (
            $userId,
            $adminView,
            &$running_balance,
            &$total_debit,
            &$total_credit
        ) {
            $item->debit = 0;
            $item->credit = 0;
            $item->account_name = $item->to == $userId
                ? ($item->creditedFrom?->name ?? $item->from)
                : ($item->creditedTo?->name ?? $item->to);
            $item->payment_method = match ((string) $item->pay_by) {
                '1' => 'UPI',
                '0' => 'Cash',
                default => 'Not specified',
            };
            $item->date = $item->transaction_date
                ? Carbon::parse($item->transaction_date)->format('d-m-Y')
                : null;
            $balanceDelta = self::ledgerBalanceDelta($item, $userId, $adminView);
            if ($balanceDelta > 0) {
                $item->debit = abs($balanceDelta);
                $total_debit += $item->amount;
            } elseif ($balanceDelta < 0) {
                $item->credit = abs($balanceDelta);
                $total_credit += $item->amount;
            }
            $running_balance += $balanceDelta;
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
        return $transactions;
    }
    public static function getLedgerDataExport(
        $request,
        ?string $accountUserId = null,
        ?string $accountName = null,
        bool $adminView = false
    )
    {
        $user_profile = auth()->user();
        $userId       = $accountUserId ?? $user_profile->user_id;
        $opening_balance = 0;
        $total_debit     = 0;
        $total_credit    = 0;
        // $search_type = $request->search_type;
        // $value = $request->value;
        $pos_name = PosModel::where('user_id', $userId)->value('name');
        $from = $request->from_date ?? $request->transaction_date;
        $to = $request->to_date ?? $request->transaction_date;
        $base_query = self::ledgerQueryForAccount($userId)
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc');
        if ($from) {
            $openingTransactions = (clone $base_query)
                ->whereDate('transaction_date', '<', $from)
                ->get();
            foreach ($openingTransactions as $transaction) {
                $opening_balance += self::ledgerBalanceDelta($transaction, $userId, $adminView);
            }
        }
        $query = (clone $base_query)
            ->with(['creditedTo', 'creditedFrom']);

        if ($from && $to) {
            $query->whereBetween('transaction_date', [$from, $to]);
        } elseif ($from) {
            $query->whereDate('transaction_date', '>=', $from);
        } elseif ($to) {
            $query->whereDate('transaction_date', '<=', $to);
        }
        $running_balance = $opening_balance;
        $transactions = $query->get()->map(function ($item) use (
            $userId,
            $adminView,
            &$running_balance,
            &$total_debit,
            &$total_credit
        ) {
            $item->debit = 0;
            $item->credit = 0;
            $item->account_name = $item->to == $userId
                ? ($item->creditedFrom?->name ?? $item->from)
                : ($item->creditedTo?->name ?? $item->to);
            $item->payment_method = match ((string) $item->pay_by) {
                '1' => 'UPI',
                '0' => 'Cash',
                default => 'Not specified',
            };
            $item->date = $item->transaction_date
                ? Carbon::parse($item->transaction_date)->format('d-m-Y')
                : null;
            $balanceDelta = self::ledgerBalanceDelta($item, $userId, $adminView);
            if ($balanceDelta > 0) {
                $item->debit = abs($balanceDelta);
                $total_debit += $item->amount;
            } elseif ($balanceDelta < 0) {
                $item->credit = abs($balanceDelta);
                $total_credit += $item->amount;
            }
            $running_balance += $balanceDelta;
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
            'name' => $accountName ?? $user_profile->name,
            'total_debit' => number_format(abs($total_debit), 2, '.', ''),
            'total_credit' => number_format(abs($total_credit), 2, '.', ''),
            'total_balance' => number_format(abs($total_balance), 2, '.', ''),
            'total_balance_type' => $total_balance_type,
            'pos_name' => $pos_name
        ];
    }

    private static function ledgerBalanceDelta(self $transaction, string $userId, bool $adminView = false): float
    {
        $balanceDelta = self::accountBalanceDelta($transaction, $userId);

        return $adminView
            ? -$balanceDelta
            : $balanceDelta;
    }

    private static function accountBalanceDelta(self $transaction, string $userId): float
    {
        $amount = (float) $transaction->amount;

        if ($transaction->remark === 'Auto Generated') {
            if ($transaction->from === $userId) {
                return $amount;
            }

            if ($transaction->to === $userId) {
                return -$amount;
            }
        }

        if ($transaction->remark === 'DSR Summary Settled' && $transaction->payment_summury_id !== null) {
            if ($transaction->to === $userId) {
                return -$amount;
            }

            if ($transaction->from === $userId) {
                return $amount;
            }
        }

        if ($transaction->to === $userId) {
            return $amount;
        }

        if ($transaction->from === $userId) {
            return -$amount;
        }

        return 0;
    }

    private static function ledgerQueryForAccount(string $userId): Builder
    {
        return self::query()
            ->where(function (Builder $query) use ($userId) {
                $query->where('to', $userId)
                    ->orWhere('from', $userId);
            })
            ->where(function (Builder $query) {
                $query->whereNull('payment_summury_id')
                    ->orWhere('remark', 'Auto Generated')
                    ->orWhereDoesntHave('payment_summary', function (Builder $summaryQuery) {
                        $summaryQuery->where('status', '!=', 'approved');
                    });
            })
            ->with(['creditedTo', 'creditedFrom']);
    }

    public static function getReceipt($request)
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->user_id;
        $from = $request->from_date;
        $to = $request->to_date;

        $query = self::where('to', $userId)
            ->where(function ($query) {
                $query->whereNull('remark')
                    ->orWhere('remark', '!=', 'Auto Generated');
            })
            ->with('creditedFrom');
        if ($from && $to) {
            $query->whereBetween('transaction_date', [$from, $to]);
        } elseif ($from) {
            $query->whereDate('transaction_date', '>=', $from);
        } elseif ($to) {
            $query->whereDate('transaction_date', '<=', $to);
        }
        $receipts = $query->orderBy('transaction_date')->orderBy('id')->get();
        $posUserIds = $receipts->pluck('from')->filter()->unique()->values();

        if ($receipts->isEmpty()) {
            return [
                'rows' => collect(),
                'total_debit' => '0.00',
                'total_credit' => '0.00',
            ];
        }

        $history = self::query()
            ->where(function ($historyQuery) use ($userId, $posUserIds) {
                $historyQuery->where('to', $userId)
                    ->orWhere('from', $userId)
                    ->orWhereIn('to', $posUserIds)
                    ->orWhereIn('from', $posUserIds);
            })
            ->orderBy('transaction_date')
            ->orderBy('id')
            ->get();

        $cashBankBalances = [];
        $posBalances = [];
        $receiptBalances = [];
        $receiptIds = $receipts->pluck('id')->flip();

        foreach ($history as $transaction) {
            $amount = (float) $transaction->amount;
            $isAutoGenerated = $transaction->remark === 'Auto Generated';

            if (!$isAutoGenerated && ($transaction->to === $userId || $transaction->from === $userId)) {
                $method = match ((string) $transaction->pay_by) {
                    '1' => '1',
                    '0' => '0',
                    default => 'unspecified',
                };
                $cashBankBalances[$method] = ($cashBankBalances[$method] ?? 0)
                    + ($transaction->to === $userId ? $amount : -$amount);
            }

            if ($posUserIds->contains($transaction->to)) {
                $posBalances[$transaction->to] = ($posBalances[$transaction->to] ?? 0) + $amount;
            }
            if ($posUserIds->contains($transaction->from)) {
                $posBalances[$transaction->from] = ($posBalances[$transaction->from] ?? 0) - $amount;
            }

            if ($receiptIds->has($transaction->id)) {
                $method = match ((string) $transaction->pay_by) {
                    '1' => '1',
                    '0' => '0',
                    default => 'unspecified',
                };
                $receiptBalances[$transaction->id] = [
                    'cash_bank' => $cashBankBalances[$method] ?? 0,
                    'pos' => $posBalances[$transaction->from] ?? 0,
                ];
            }
        }

        $rows = $receipts->reverse()->values()->map(function ($item) use ($receiptBalances) {
            $method = match ((string) $item->pay_by) {
                '1' => 'UPI',
                '0' => 'Cash',
                default => 'Not specified',
            };
            $account = match ((string) $item->pay_by) {
                '1' => 'Bank (UPI)',
                '0' => 'Cash',
                default => 'Cash/Bank',
            };
            $balances = $receiptBalances[$item->id] ?? ['cash_bank' => 0, 'pos' => 0];
            $amount = number_format((float) $item->amount, 2, '.', '');

            return [
                'id' => $item->id,
                'voucher' => $item->voucher_number,
                'ref_number' => $item->reference_number,
                'date' => $item->transaction_date,
                'receive_from' => $item->creditedFrom?->name ?? $item->from,
                'receive_by' => $method,
                'cash_bank_account' => $account,
                'amount' => $amount,
                'debit_balance' => number_format(abs($balances['cash_bank']), 2, '.', ''),
                'debit_balance_type' => $balances['cash_bank'] >= 0 ? 'Dr' : 'Cr',
                'credit_balance' => number_format(abs($balances['pos']), 2, '.', ''),
                'credit_balance_type' => $balances['pos'] >= 0 ? 'Dr' : 'Cr',
                'remark' => $item->remark,
            ];
        });
        $totalAmount = number_format((float) $receipts->sum('amount'), 2, '.', '');
        return [
            'rows' => $rows,
            'total_debit' => $totalAmount,
            'total_credit' => $totalAmount,
        ];
    }
}
