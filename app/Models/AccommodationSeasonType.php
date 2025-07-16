<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccommodationSeasonType extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function season() {
        return $this->belongsTo(Season::class, 'season_type_id', 'id');
    }
}
