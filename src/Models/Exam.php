<?php

namespace Medigeneit\MasterGenesis\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $table = 'exam';
     
    protected $casts = [
        'question_format' => 'json',
    ];

}
