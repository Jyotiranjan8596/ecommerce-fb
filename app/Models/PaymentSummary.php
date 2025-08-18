<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PaymentSummary extends Model
{
    use HasFactory;
    protected $fillable = [
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

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function store_summary($data)
    {
        try {
            $existing = self::where('date', $data['date'])
                ->where('created_by', auth()->id())
                ->first();

            if ($existing) {
                // already exists → don't create again
                return 1;
            }
            $res = self::create([
                'date'                 => $data['date'],
                'total_transaction'    => $data['total_transactions'],
                'total_billing_amount' => $data['billing_amount'],
                'by_cash'              => $data['cash_upi'],
                'by_wallet'            => $data['wallet'],
                'by_reward'            => $data['reward'],
                'pos_credit'           => $data['credit_amount'],
                'pos_debit'            => $data['debit_amount'],
                'admin_credit'         => $data['debit_amount'],
                'admin_debit'          => $data['credit_amount'],
                'status'               => 'pending',
                'created_by'           => auth()->user()->id,
                // 'updated_by' => auth()->user()->id,
            ]);
            if ($res) {
                return true;
            }
        } catch (\Exception $e) {
            Log::info('Summary Creation' . $e->getMessage());
            return false;
        }
    }

    public static function fetch_summary_pos()
    {
        $resp = self::where('created_by', auth()->user()->id)->orderBy('id', 'desc')->get()->map(function ($item) {
            $item->intiate_date = Carbon::parse($item->date)->format('d-m-Y');
            return $item;
        });
        return $resp;
    }

    public static function fetch_summary_admin()
    {
        $resp = self::with('creator')->orderBy('id', 'desc')->get()->map(function ($item) {
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
}
