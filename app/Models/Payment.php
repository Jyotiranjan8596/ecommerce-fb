<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        try {
            $user = auth()->user();

            $pos = PosModel::find($request->pos_id);

            if (!$pos) {
                return false;
            }

            $data = [
                'transaction_date' => Carbon::today()->toDateString(),
                'voucher_number'   => self::generateVoucherNumber(),
                'reference_number' => $request->reference_number,
                'account_details'  => null,
                'due'              => 0,
                'credit'           => 0,
                'debit'            => $request->paying_amount,
                'credited_to'      => $pos->user_id,
                'created_by'       => $user->user_id,
                'updated_by'       => $user->user_id,
                'remark'           => $request->remark,
            ];

            return Payment::create($data) ? true : false;
        } catch (\Throwable $e) {
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
        // $year = $request->year;
        // $month = $request->month;
        // $type = $request->type;
        // $pay_by = $request->pay_by;
        $query = self::where('created_by', $userId)->with('createdBy')->orderBy('id', 'desc');
        // if ($month && $year) {

        //     $formatted = date('M', mktime(0, 0, 0, $month, 1)) . '-' . substr($year, -2);
        //     $format2 = substr($year, -2) . '-' . date('M', mktime(0, 0, 0, $month, 1));

        //     $query->where(function ($q) use ($formatted, $format2) {
        //         $q->where('month', $formatted)
        //             ->orWhere('month', $format2);
        //     });
        // }

        // if ($month && !$year) {

        //     $formatted = date('M', mktime(0, 0, 0, $month, 1));

        //     $query->where('month', 'LIKE', $formatted . '-%');
        // }

        // if ($year && !$month) {

        //     $shortYear = substr($year, -2);

        //     $query->where('month', 'LIKE', '%-' . $shortYear);
        // }

        // if ($type && $type != 'all') {

        //     $query->where('trans_type', $type);
        // }

        // if ($pay_by == 'wallet') {

        //     $query->where('wallet_amount', '!=', '0');
        // }

        // if ($pay_by == 'reward') {

        //     $query->where('used_points', '>', '0');
        // }
        return $query->paginate(50)->through(function ($item) {
            // dd($wallet->toArray());
            $item->date = $item->transaction_date
                ? Carbon::parse($item->transaction_date)->format('d-m-Y')
                : null;
            return $item;
        });
    }
}
