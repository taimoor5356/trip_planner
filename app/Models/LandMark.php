<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandMark extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    
    public function city() {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
    
    public function image() {
        return $this->hasOne(LandMarkImage::class, 'land_mark_id', 'id');
    }

    public function activities() {
        return $this->hasMany(LandMarkActivity::class, 'land_mark_id', 'id');
    }
}
