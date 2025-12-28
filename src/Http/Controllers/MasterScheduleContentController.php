<?php

namespace Medigeneit\MasterGenesis\Http\Controllers;


use App\ScheduleTimeSlot;
use App\ScheduleSlotEditableTimeBasic;
use App\ScheduleSlotEditableTime;
use App\ScheduleSlotEditableTimeFaculty;
use App\ScheduleSlotEditableTimeMock;
use App\BatchesSchedules;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Medigeneit\MasterGenesis\Http\Resources\MasterScheduleContentResource;
use Medigeneit\MasterGenesis\Models\Exam;
use Medigeneit\MasterGenesis\Models\LectureVideo;
use Medigeneit\MasterGenesis\Models\ScheduleSlot;

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

  public function booking_batches($bookingId)
  {

    $slot = ScheduleSlot::query();

    $slots = $slot->where('booking_id', $bookingId)->get();

    if (count($slots) == 1) {

      $slot = $slots[0];




      $schedules = BatchesSchedules::query()
        ->with([
          'batch',
          'batch:id,name,year,course_id',
          'batch.course:id,name',
          'subject:id,name',
          'faculty:id,name'
        ])->when(($slot->type == 'Basic'), function ($q) use ($slot) {
          $q->where('basic_id', $slot->batch_schedule_id);
        })
        ->when(($slot->type == 'Clinical'), function ($q) use ($slot) {
          $q->where('clinical_id', $slot->batch_schedule_id);
        })
        ->when(($slot->type == 'Faculty'), function ($q) use ($slot) {
          $q->where('faculty_schedule_id', $slot->batch_schedule_id);
        })
        ->when(($slot->type == 'Mock'), function ($q) use ($slot) {
          $q->where('mock_schedule_id', $slot->batch_schedule_id);
        })
        ->when(
          ($slot->type == '' || in_array($slot->type, ['Clinical-Editable', 'Basic-Editable', 'Faculty-Editable'])),
          function ($q) use ($slot) {
            $q->where('id', $slot->batch_schedule_id);
          }
        )
        ->get();


      return [
        'batches' => $schedules->map(function ($schedule) {
          return [
            'id' => $schedule->batch->id,
            'name' => $schedule->batch->name,
            'id' => $schedule->batch->id,
            'year' => $schedule->batch->year,
            'course' => $schedule->batch->course,
            'subject' => $schedule->subject,
            'faculty' => $schedule->faculty,
          ];
        }),
        'schedule_ids' => $schedules->pluck('id'),
        'slots' => $slots,
      ];

      return $slot;
    } else {
    }

    return [
      'batches' => [],
      'schedule_ids' => [],
      'slots' => $slots,
    ];



    $slotQuery = ScheduleTimeSlot::query()->where('booking_id', $bookingId);
    $slot = $slotQuery->get('schedule_id');
    $schedules = BatchesSchedules::query();


    /********* Basic Schedule query **********/
    $basicScheduleSlotQuery = ScheduleSlotEditableTimeBasic::query()->where('booking_id', $bookingId);
    $basicSlotQuery =  ScheduleTimeSlot::query()->whereIn('id', $basicScheduleSlotQuery->pluck('schedule_time_slot_id'));
    $basic_slot = $basicSlotQuery->get(['schedule_id']);

    /*****************************/

    /****** Clinical Schedule Slot */
    $clinicalScheduleSlotQuery = ScheduleSlotEditableTime::query()->where('booking_id', $bookingId);
    $clinicalSlotQuery =  ScheduleTimeSlot::query()->whereIn('id', $clinicalScheduleSlotQuery->pluck('schedule_time_slot_id'));
    $clinical_slot = $clinicalSlotQuery->get(['schedule_id']);

    /****** Clinical Schedule Slot */
    $facultyScheduleSlotQuery = ScheduleSlotEditableTimeFaculty::query()->where('booking_id', $bookingId);
    $facultySlotQuery =  ScheduleTimeSlot::query()->whereIn('id', $facultyScheduleSlotQuery->pluck('schedule_time_slot_id'));
    $faculty_slot = $facultySlotQuery->get(['schedule_id']);

    $mockScheduleSlotQuery = ScheduleSlotEditableTimeMock::query()->where('booking_id', $bookingId);
    $mockSlotQuery =  ScheduleTimeSlot::query()->whereIn('id', $mockScheduleSlotQuery->pluck('schedule_time_slot_id'));
    $mock_slot = $mockSlotQuery->get(['schedule_id']);





    // $scheduleQuery = $slotQuery->get();
    // $disciplineScheduleSlot = $clinicalScheduleSlotQuery->get();
    // $facultyScheduleSlot = $facultyScheduleSlotQuery->get();
    // $mockScheduleSlot = $mockScheduleSlotQuery->get();


    // return $slot->pluck('schedule_id')->all();

    return
      $schedules = BatchesSchedules::query()
      ->with([
        'batch',
        'batch:id,name,year,course_id',
        'batch.course:id,name',
        'subject',
        'faculty'
      ])
      ->where(function ($q) use ($slot, $basic_slot,  $clinical_slot, $faculty_slot, $mock_slot) {

        $q->whereIn('id', $slot->pluck('schedule_id')->all());

        $q->orWhereIn('basic_id', $basic_slot->pluck('schedule_id')->all());

        $q->orWhereIn('clinical_id', $clinical_slot->pluck('schedule_id')->all());

        $q->orWhereIn('faculty_schedule_id', $faculty_slot->pluck('schedule_id')->all());

        $q->orWhereIn('mock_schedule_id', $mock_slot->pluck('schedule_id')->all());
      })
      // ->get(['id', 'name', 'year', 'batch_id', 'session_id']);
      ->get();

    return [
      'batches' => $schedules->map(function ($schedule) {
        return [
          'id' => $schedule->batch->id,
          'name' => $schedule->batch->name,
          'id' => $schedule->batch->id,
          'year' => $schedule->batch->year,
          'course' => $schedule->batch->course,
        ];
      }),
      'schedule_ids' => $schedules->pluck('id')
    ];
  }
}
