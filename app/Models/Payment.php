<?php

namespace App\Models;

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
        'transaction_date',
        'voucher_number',
        'reference_number',
        'account_details',
        'due',
        'credit',
        'debit',
        'credited_to',
        'created_by',
        'updated_by',
        'remark',
    ];

    public function creditedTo()
    {
        return $this->belongsTo(User::class, 'credited_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
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

            $voucherNumber = self::generateVoucherNumber();

            if ($request->is_pos) {
                $debitTo  = $pos->user_id;
                $creditTo = 666666;
            } else {
                $debitTo  = 666666;
                $creditTo = $pos->user_id;
            }

            $data = [
                [
                    'transaction_date' => $today,
                    'voucher_number'   => $voucherNumber,
                    'reference_number' => $request->reference_number,
                    'account_details'  => null,
                    'due'              => 0,
                    'debit'            => $request->paying_amount,
                    'credit'           => 0,
                    'credited_to'      => $debitTo,
                    'created_by'       => $user->user_id,
                    'updated_by'       => $user->user_id,
                    'remark'           => $request->remark,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ],
                [
                    'transaction_date' => $today,
                    'voucher_number'   => $voucherNumber,
                    'reference_number' => $request->reference_number,
                    'account_details'  => null,
                    'due'              => 0,
                    'debit'            => 0,
                    'credit'           => $request->paying_amount,
                    'credited_to'      => $creditTo,
                    'created_by'       => $user->user_id,
                    'updated_by'       => $user->user_id,
                    'remark'           => $request->remark,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]
            ];

            $updated = PaymentSummary::where('date', $request->summary_date)
                ->where('pos_id', $pos->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'approved',
                    'reference_number' => $request->reference_number
                ]);

            if ($updated == 0) {
                DB::rollBack();
                return false;
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

    public static function generateVoucherNumber()
    {
        $today = Carbon::now()->format('Ymd');

        $lastPayment = Payment::latest('id')->first();

        $sequence = $lastPayment
            ? ((int) substr($lastPayment->voucher_number, -4)) + 1
            : 1;

        return 'FBR-' . $today . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public static function getLedgerData($request)
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->user_id;
        $search_type = $request->search_type;
        $value = $request->value;
        $query = self::where('credited_to', $userId)->with('createdBy')->orderBy('id', 'desc');
        if ($search_type == 'date') {
            $query->where('transaction_date', $value);
        }
        if ($search_type == 'voucher') {
            $query->where('voucher_number', $value);
        }
        if ($search_type == 'ref') {
            $query->where('reference_number', $value);
        }
        return $query->paginate(50)->through(function ($item) {
            // dd($wallet->toArray());
            $item->date = $item->transaction_date
                ? Carbon::parse($item->transaction_date)->format('d-m-Y')
                : null;
            return $item;
        });
    }
    public static function getLedgerDataExport($request)
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->user_id;
        $search_type = $request->search_type;
        $value = $request->value;
        $query = self::where('credited_to', $userId)->with('createdBy')->orderBy('id', 'desc');
        if ($search_type == 'date') {
            $query->where('transaction_date', $value);
        }
        if ($search_type == 'voucher') {
            $query->where('voucher_number', $value);
        }
        if ($search_type == 'ref') {
            $query->where('reference_number', $value);
        }
        return $query->get()->map(function ($item) {
            $item->date = $item->transaction_date
                ? Carbon::parse($item->transaction_date)->format('d-m-Y')
                : null;
            return $item;
        });
    }
}
