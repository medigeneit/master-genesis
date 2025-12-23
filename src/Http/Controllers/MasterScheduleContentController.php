<?php

namespace Medigeneit\MasterGenesis\Http\Controllers;


use App\ScheduleTimeSlot;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Medigeneit\MasterGenesis\Http\Resources\MasterScheduleContentResource;
use Medigeneit\MasterGenesis\Models\Exam;
use Medigeneit\MasterGenesis\Models\LectureVideo;


class MasterScheduleContentController extends Controller
{
  //

  public function index(Request $request)
  {

    $slot = ScheduleTimeSlot::query();

    $slot->with(
      [
        'schedule_details.lecture',
        'schedule_details.exam',
        'schedule_details.lectures.lecture',
      ]
    );

    $slot->whereNotNull('booking_id');
    // return $slot->limit(10)->get();

    $slot
      ->select(
        'schedule_time_slots.id',
        'bs.type',
        'bs.course_id',
        'schedule_time_slots.datetime',
        'schedule_time_slots.booking_id',
        'schedule_time_slots.schedule_id',
      )
      ->join(
        'batches_schedules as bs',
        function ($join) {
          $join->on('bs.id', '=', 'schedule_time_slots.schedule_id');
        }
      )
    ;

    $booking_ids = is_array($request->booking_ids) && count($request->booking_ids) ? $request->booking_ids :  [];

    $slot->when(count($booking_ids), function ($q)  use ($booking_ids) {
      $q->whereIn('booking_id', $booking_ids);
    });

    // return
    $slots = $slot->get();

    return MasterScheduleContentResource::collection($slots);
  }

  public function show() {}
}
