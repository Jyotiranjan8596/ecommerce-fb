<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoGraphic extends Model
{
    use HasFactory;
    protected $casts = [
        'images' => 'array',
    ];

    public function sector(){
        return $this->belongsTo(Sector::class,'sector_id');
    }
    public function subsector(){
        return $this->belongsTo(SubSector::class,'subsector_id');
    }

}
