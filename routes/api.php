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

Route::prefix('/batches')->group(function () {
  Route::get('/', [BatchController::class, 'batches']);
  Route::get('/{batch}', [BatchController::class, 'batch_info']);
  Route::patch('/{batch}', [BatchController::class, 'update_batch_module']);
});

Route::get('/faculty-data/all', [BatchController::class, 'faculties']);
Route::get('/subject-data/all', [BatchController::class, 'subjects']);