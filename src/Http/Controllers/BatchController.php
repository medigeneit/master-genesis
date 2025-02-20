<?php

namespace Medigeneit\MasterGenesis\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Medigeneit\MasterGenesis\Resources\BatchInfoForBookingCollection;
use Medigeneit\MasterGenesis\Resources\BatchInfoForBookingResource;
use Medigeneit\MasterGenesis\Resources\FacultyInfoForBookingResource;
use Medigeneit\MasterGenesis\Resources\SubjectInfoForBookingResource;
use Medigeneit\MasterGenesis\Models\Batch;

class BatchController extends Controller
{
  function batch_info(Batch $batch)
  {

    // $batch = Batches::not_expired()->find($batchId);

    return response(
      [
        'status' => true,
        'exists' => true,
        'batch' => BatchInfoForBookingResource::make($batch),
        'message' => ""
      ]
    );
  }

  function batches(Request $request)
  {

    // return self::class;

    $batches =  Batch::query();

    $batches->with([
      'session',
      'course.institute'
    ]);

    $batches->where(function ($batches) use ($request) {

      $courseCategoryIds = $request->course_category_ids ? explode(",", ($request->course_category_ids ?? "")) : [];

      $batches->where(function ($batches) use ($courseCategoryIds) {

        $batches->when($courseCategoryIds, function ($batches, $courseCategoryIds) {
          if (count($courseCategoryIds)) {

            $batches->whereIn(
              'id',
              DB::table('available_batches')
                ->select('batch_id')
                ->whereIn('course_category_id', $courseCategoryIds)
            );

            //$batches->whereIn('course_category_id', $courseCategoryIds);
          }
        });
      });

      $batchIds = $request->batch_ids ? explode(",", ($request->batch_ids ?? "")) : [];

      $batches->orWhere(function ($batches) use ($batchIds) {
        $batches->when(count($batchIds), function ($batches) use ($batchIds) {
          $batches->whereIn('id', $batchIds);
        });
      });
    });

    $batches->when($request->search, function ($batches, $search_text) {
      $search_text = trim($search_text);

      $searcher = function ($search_queries) use ($batches) {
        $batches->where(function ($batches) use ($search_queries) {
          foreach ($search_queries as $search) {
            $batches->orWhere('name',  'LIKE', "%{$search}%");
          }

          $batches->orWhereHas('course', function ($courses) use ($search_queries) {
            foreach ($search_queries as $i => $search) {
              $courses->{$i === 0 ? 'where' : 'orWhere'}('name',  'LIKE', "%{$search}%");
            }
          });
        });
      };

      /**
       * If the search text has double quotes 
       * at the start and end, search for the exact text 
       * inside the quotes.
       */
      if (preg_match('/^"(.*)"$/', $search_text, $matches)) {
        //echo $matches[1]; // Output: Hello, World!
        return $searcher([$matches[1]]);
      }

      $searches = explode(" ", $search_text);

      if (count($searches)) {
        $searcher($searches);
      }
      $batches->orWhere('id', $search_text);
    });

    $batches->latest('id');

    // return [$batches->toSql(), $batches->getBindings()];

    return BatchInfoForBookingCollection::make(
      $batches->paginate(
        $request->get('perpage', 15)
      )
    );
  }

  function update_batch_module(Batch $batch, Request $request)
  {
    
    $request->validate(['moduleId' => 'required']);

    $batch->update(['module_id' => $request->moduleId]);

    return response(['status' => true, 'message' => "Success!"]);
  }

  function subjects(Request $request)
  {
    $subject_ids = $request->subject_ids ?? '';

    $subjects = \Medigeneit\MasterGenesis\Models\Subject::query()->active();

    $subjects->with('course');

    $subjects->where('course_id', config('master-genesis.FCPSP1_COURSE_ID'));

    $subjects->when($subject_ids, function ($subjects, $subject_ids) {
      $subjects->whereIn('id', explode(",", $subject_ids));
    });

    $chunkedSubjects = [];
    $subjects->chunkById(500, function ($chunk) use (&$chunkedSubjects) {
        foreach ($chunk as $subject) {
            $chunkedSubjects[] = $subject;
        }
    });

    return response([
      'subjects' => SubjectInfoForBookingResource::collection($chunkedSubjects),
    ]);
  }

  function faculties(Request $request)
  {
    $course_ids = $request->course_ids ?? '';
    $faculty_ids = $request->faculty_ids ?? '';

    $faculties = \Medigeneit\MasterGenesis\Models\Faculty::query()->active();

    $faculties->with('course');

    $faculties->when($course_ids, function ($faculties, $course_ids) {
      $faculties->whereIn('course_id', explode(",", $course_ids));
    });

    $faculties->when($faculty_ids, function ($faculties, $faculty_ids) {
      $faculties->whereIn('id', explode(",", $faculty_ids));
    });

    // return [
    //     'sql' => $faculties->toSql(),
    //     'binding' => $faculties->getBindings(),
    // ];

    $chunkedFaculties = [];
    $faculties->chunkById(500, function ($chunk) use (&$chunkedFaculties) {
        foreach ($chunk as $faculty) {
            $chunkedFaculties[] = $faculty;
        }
    });

    return response([
      'faculties' => FacultyInfoForBookingResource::collection($chunkedFaculties),
    ]);
  }
}
