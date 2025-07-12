<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Origin extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function originDestinations()
    {
        return $this->hasMany(OriginDestination::class, 'origin_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(OriginImage::class, 'origin_id', 'id');
    }

    public function getDestinationNamesListAttribute() {
        $ids = json_decode($this->destination_ids ?: '[]');
        return Region::whereIn('id', $ids)->pluck('name')->implode(', ');
    }
}
