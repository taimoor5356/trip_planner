<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accommodation extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function building() {
        return $this->belongsTo(BuildingType::class, 'building_type_id', 'id');
    }

    public function town() {
        return $this->belongsTo(Town::class, 'town_id', 'id');
    }

    public function roomCategories() {
        return $this->hasMany(RoomCategoryCost::class, 'accommodation_id', 'id');
    }

    public function getBuiltNamesListAttribute() {
        $ids = json_decode($this->built_id ?: '[]');
        return Built::whereIn('id', $ids)->pluck('name')->implode(', ');
    }

    public function getCategoryNamesListAttribute() {
        $ids = json_decode($this->category_id ?: '[]');
        return Category::whereIn('id', $ids)->pluck('name')->implode(', ');
    }

    public function getAmenityNamesListAttribute()
    {
        $ids = json_decode($this->property_amenities_id ?: '[]');
        return PropertyAmenity::whereIn('id', $ids)->pluck('name')->implode(', ');
    }

    public function images() {
        return $this->hasMany(AccommodationImage::class, 'accommodation_id', 'id');
    }
}
