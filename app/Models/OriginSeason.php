<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OriginSeason extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function origin()
    {
        return $this->belongsTo(Origin::class, 'origin_id', 'id');
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id', 'id');
    }
}
