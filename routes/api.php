<?php

namespace Medigeneit\MasterGenesis\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/batch-data')->group(function () {

  Route::get('/{batch_id}', [BatchController::class, 'batch_info']);

  Route::get('/batches/all', [BatchController::class, 'batches']);

  Route::get('/batches/{batch}/schedules', [ScheduleController::class, 'index']);

  Route::patch('/batches/{batch_id}/update-module-id/{module_id}', [BatchController::class, 'update_batch_module']);
});

Route::get('/faculty-data/all', [BatchController::class, 'faculties']);

Route::get('/subject-data/all', [BatchController::class, 'subjects']);

Route::post('/course-data/courses/{course}/department-token', [CourseController::class, 'save_department_token']);
