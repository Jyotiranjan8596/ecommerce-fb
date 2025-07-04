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
        'wallet_id',
        'invoice',
        'month',
        'used_amount',
        'wallet_amount',
        'remaining_amount',
        'reward_points',
        'used_points',
        'remaining_points',
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

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }
}
