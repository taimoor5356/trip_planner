<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function image()
    {
        return $this->hasMany(CityImage::class, 'city_id', 'id');
    }
}
