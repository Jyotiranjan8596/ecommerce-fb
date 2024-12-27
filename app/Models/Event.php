<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public function eventCategory()
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }
    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

}
