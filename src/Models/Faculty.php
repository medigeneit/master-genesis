<?php

namespace Medigeneit\MasterGenesis\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
