<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    public $timestamps = true;
    public $table = 'sponsors';
    protected $fillable = [
        'user_id',
        'sponsor_id',
        'created_at',
        'updated_at'

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
