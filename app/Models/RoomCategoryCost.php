<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomCategoryCost extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id', 'id');
    }

    public function roomCategory()
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id', 'id');
    }
}
