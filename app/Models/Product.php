<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'sector_id',
        'title',
        'description',
        'bestseller',
        'image',
        'price',
        'discount_price',
        'total_price'
    ];



    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    public function subsector()
    {
        return $this->belongsTo(SubSector::class, 'subsector_id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'product_id');
    }
}
