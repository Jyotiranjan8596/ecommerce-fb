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
        'user_id',
        'image',
        'name',
        'email',
        'mobilenumber',
        'upi_id',
        'transaction_charge',
        'min_charge',
        'max_charge',
        'initial_letter_of_invoice',
        'pos_code',
        'entity_name',
        'entity_address',
        'entity_contact',
        'comment',
        'address',
        'city',
        'state',
        'zip',
        'latitude',
        'longitude'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function wallet()
    {
        return $this->hasMany(Wallet::class);
    }

    public static function getPosByUpi($name)
    {
         parse_str(parse_url($name, PHP_URL_QUERY), $params);
         $upi_id = $params['pa'];
        return self::where('upi_id', $upi_id)->first();
    }
}
