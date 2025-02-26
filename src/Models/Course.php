<?php

namespace Medigeneit\MasterGenesis\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['department_token'];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
