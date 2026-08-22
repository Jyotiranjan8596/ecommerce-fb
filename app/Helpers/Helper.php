<?php

namespace App\Helpers;

use App\Models\PosModel;

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
}
