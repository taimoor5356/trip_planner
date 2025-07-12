<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function origin() {
        return $this->belongsTo(Origin::class, 'origin_id', 'id');
    }

    public function destination() {
        return $this->belongsTo(Region::class, 'destination_id', 'id');
    }

    public function images() {
        return $this->hasMany(ItineraryImage::class, 'itinerary_id', 'id');
    }
}
