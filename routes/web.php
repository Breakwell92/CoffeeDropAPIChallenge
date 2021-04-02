<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LocationController;
use App\Http\Controllers\CashbackCalculationController;

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

Route::get('/', function () {
    $days_of_week = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    return view('welcome', compact('days_of_week'));
});

Route::post('/get-nearest-location', [LocationController::class,'getNearestLocation'])->name('get-nearest-location');
Route::post('/create-location', [LocationController::class,'storeNewLocation'])->name('create-location');
Route::post('/calculate-cashback', [CashbackCalculationController::class,'calculateCashback'])->name('calculate-cashback');
Route::post('/cashback-calc-requests', [CashbackCalculationController::class,'getRequests'])->name('cashback-calc-requests');