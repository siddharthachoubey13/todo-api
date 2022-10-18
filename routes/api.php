<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Task;
use App\Http\Controllers\SubTask;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'task'], function () {
    Route::post('create', [Task::class, 'create']);
    Route::get('search', [Task::class, 'search']);
    Route::delete('delete', [Task::class, 'delete']);
    Route::put('mark', [Task::class, 'markStatus']);
});

Route::group(['prefix' => 'subtask'], function () {
    Route::post('create', [SubTask::class, 'create']);
    Route::get('search', [SubTask::class, 'search']);
    Route::delete('delete', [SubTask::class, 'delete']);
    Route::put('mark', [SubTask::class, 'markStatus']);
});

