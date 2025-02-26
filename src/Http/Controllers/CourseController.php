<?php

namespace Medigeneit\MasterGenesis\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Medigeneit\MasterGenesis\Models\Course;

class CourseController extends Controller
{

  public function save_department_token(Course $course, Request $request)
  {

    $course->update([
      'department_token' => $request->token,
    ]);
  }
}
