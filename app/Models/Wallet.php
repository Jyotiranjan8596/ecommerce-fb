<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'description',
        'invoice',
        'billing_amount',
        'amount',
        'pay_by', 
        'tran_type',
        'transaction_amount', 
        'pay_by_wallet', 
        'amount_wallet', 
        'user_id',
        'pos_id',
        'insert_by',
        'mobilenumber',
        'transaction_date',
        'status',
        'insert_date',
    ];
   

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function getPos()
    {
        return $this->belongsTo(PosModel::class, 'pos_id','id');
    }
}
