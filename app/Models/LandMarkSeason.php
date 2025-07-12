<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandMarkSeason extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function landmark()
    {
        return $this->belongsTo(LandMark::class, 'landmark_id', 'id');
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id', 'id');
    }
}
