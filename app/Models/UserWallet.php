<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'pos_id',
        'invoice',
        'month',
        'used_amount',
        'wallet_amount',
        'pay_by',
        'trans_type',
        'transaction_date',
        'mobilenumber'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function getPos()
    {
        return $this->belongsTo(PosModel::class, 'pos_id');
    }
}
