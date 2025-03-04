<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeUnit\FunctionUnit;

class PosModel extends Model
{
    use HasFactory;
    protected $table = 'pos_systems';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'upi_id',
        'transaction_charge',
        'min_charge',
        'max_charge',
        'initial_letter_of_invoice',
        'pos_code',
        'entity_name',
        'entity_address',
        'user_id',
        'entity_contact',
        'comment',
        'address',
        'city',
        'state',
        'zip',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function wallet(){
        return $this->hasMany(Wallet::class);
    }
    

}
