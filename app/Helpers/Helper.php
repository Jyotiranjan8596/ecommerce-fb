<?php

namespace App\Helpers;

use App\Models\Payment;
use App\Models\PosModel;
use Carbon\Carbon;

class Helper
{
    public static function get_pos_id($user_id)
    {
        return PosModel::where('user_id', $user_id)->value('id');
    }

    public static function formatAmount($amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }

    public static function generateVoucherNumber($autocreate = false)
    {
        $today = Carbon::now()->format('Ymd');
        $result = null;
        $lastPayment = Payment::latest('id')->first();
        if ($autocreate) {
            $sequence = $lastPayment
                ? ((int) substr($lastPayment->voucher_number, -4)) + 1
                : 1;

            $result = 'FBR-ATCRT' . $today . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        } else {
            $sequence = $lastPayment
                ? ((int) substr($lastPayment->voucher_number, -4)) + 1
                : 1;

            $result =  'FBR-' . $today . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        }
        return $result;
    }

    public static function formatDate($date)
    {
        return $date ? Carbon::parse($date)->format('d-m-Y') : '';
    }
}
