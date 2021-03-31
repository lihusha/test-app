<?php

use App\Http\Controllers\CvController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\PlayController;
use App\Http\Controllers\ServiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/summary', [SummaryController::class, 'index'])->name('summary.index');
Route::get('play', [PlayController::class, 'play'])->name('play');
Route::post('service/reset-schedule', [ServiceController::class, 'resetSchedule'])
    ->name('service.reset-schedule');

Route::get('/', [CvController::class, 'show']);
