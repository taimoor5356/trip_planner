<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function regionSeasons()
    {
        return $this->hasMany(RegionSeason::class, 'region_id', 'id');
    }

    public function image()
    {
        return $this->hasOne(RegionImage::class, 'region_id', 'id');
    }
}
