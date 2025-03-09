<?php

namespace Medigeneit\MasterGenesis\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Medigeneit\MasterGenesis\Models\Exam;
use Medigeneit\MasterGenesis\Models\LectureVideo;

class ContentController extends Controller
{
    //
    public function get_single_content($type, $searchCode)
    {
        $content = [];
        if( $type == "exam" && $searchCode ){
            $content = Exam::find($searchCode, ['id', "name"]) ?? null;
        }
        else{
            $content = LectureVideo::find($searchCode, ['id', "name"]) ?? null;
        }

        return response($content, 200);
    }

    //
    public function materials_by_ids(Request $request)
    {
        $lectures = [];
        $exams = [];
        
        $exam_ids = ($request->exam_ids ?? '' ) ? explode(',', $request->exam_ids ) : [];
        $lecture_ids = ($request->lecture_ids ?? '' ) ? explode(',', $request->lecture_ids ) : [];
        if( is_array($exam_ids) && count($exam_ids) ){
            $exams = Exam::query()->whereIn('id', $exam_ids)->get(['id', "name"]);
        }

        if( is_array($lecture_ids) && count($lecture_ids) ){
            $lectures = LectureVideo::query()->whereIn('id', $lecture_ids)->get(['id', "name"]);
        }
 

        return response([
            'exams' => $exams,
            'lectures' => $lectures,
        ], 200);
    }
}