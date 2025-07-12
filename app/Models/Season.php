<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function regions() {
        return $this->hasMany(RegionSeason::class, 'season_id', 'id');
    }
}
