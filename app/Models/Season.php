<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Season extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function regions() {
        return $this->hasMany(RegionSeason::class, 'season_id', 'id');
    }
}
