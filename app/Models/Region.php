<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use HasFactory, SoftDeletes;

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
