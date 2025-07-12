<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandMarkSeasonActivity extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function activity()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id', 'id');
    }
}
