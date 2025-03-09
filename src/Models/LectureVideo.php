<?php
namespace Medigeneit\MasterGenesis\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LectureVideo extends Model
{
    use SoftDeletes;

    //protected $table = 'lecture_videos';
    protected $table = 'lecture_video';

    public $timestamps = false;

    protected $guarded = [];

    // video type 
    const VIDEO_TYPES = [
        '1' => "Regular Class",
        '2' => "Solve Class",
        '3' => "Feedback Class",
        '4' => "Others",
    ];
}
