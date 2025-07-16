<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandMarkSeasonActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function activity()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id', 'id');
    }
}
