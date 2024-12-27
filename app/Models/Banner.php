<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [               
        'image',
        'title',
        'sub_title',
        'description'
    ];
}
