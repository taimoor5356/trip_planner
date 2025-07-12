<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccommodationSeasonType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function season() {
        return $this->belongsTo(Season::class, 'season_type_id', 'id');
    }
}
