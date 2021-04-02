<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LocationController;
use App\Http\Controllers\CashbackCalculationController;

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

Route::post('/get-nearest-location/{postcode}', [LocationController::class,'getNearestLocation'])->name('get-nearest-location');
Route::post('/create-location', [LocationController::class,'storeNewLocation'])->name('create-location');
Route::post('/calculate-cashback', [CashbackCalculationController::class,'calculateCashback'])->name('calculate-cashback');
Route::post('/cashback-calc-requests', [CashbackCalculationController::class,'getRequests'])->name('cashback-calc-requests');


