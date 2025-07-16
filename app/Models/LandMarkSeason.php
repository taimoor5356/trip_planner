<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandMarkSeason extends Model
{
    use HasFactory, SoftDeletes;

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
