<?php

namespace Medigeneit\MasterGenesis\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Medigeneit\MasterGenesis\BookingHttpFactory;
use Medigeneit\MasterGenesis\Facades\BookingHttp;
use Medigeneit\MasterGenesis\Models\Batch;


class ScheduleController extends Controller
{

  public function index(Batch $batch, Request $request)
  {
    $batch->load(['course:id,name,institute_id', 'course.institute:id,name']);

    $department_token = $batch->course->department_token ?? '';

    $bookings = BookingHttp::get(
      "/api/website-bookings",
      [
        'department_token' => $department_token,
        'batch_id' => $batch->id
      ]
    );
    return [
      'batch'  => $batch->only('id', 'name'),
      'course'  => $batch->course->only('id', 'name', 'institute'),
      'schedules' => $bookings->object()->bookings ?? []
    ];
  }
}
