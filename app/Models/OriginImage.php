<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OriginImage extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function origin() {
        return $this->belongsTo(Origin::class, 'origin_id', 'id');
    }
}
