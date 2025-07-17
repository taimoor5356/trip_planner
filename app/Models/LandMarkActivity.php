<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandMarkActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function landmark() {
        return $this->belongsTo(LandMark::class, 'land_mark_id', 'id');
    }

    public function activity() {
        return $this->belongsTo(ActivityType::class, 'activity_id', 'id');
    }
}
