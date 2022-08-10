<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerAddressController;
use App\Http\Controllers\CustomerSettingController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'customers', 'middleware' => ['auth']], function () {
    Route::controller(CustomerController::class)->group( function () {
        Route::get('', 'index');
        Route::get('{id}/show', 'show');
        Route::post('store', 'store');
        Route::put('{id}/update', 'update');
        Route::delete('{id}/destroy', 'destroy');
    });
    Route::controller(CustomerAddressController::class)->group( function () {
        Route::get('{id}/addresses/show', 'show');
        Route::post('{id}/addresses/store', 'store');
        Route::put('{id}/addresses/update', 'update');
        Route::delete('{id}/addresses/destroy', 'destroy');
    });
    Route::controller(CustomerSettingController::class)->group( function () {
        Route::post('{id}/settings/store', 'store');
        Route::put('{id}/settings/update', 'update');
        Route::delete('{id}/settings/destroy', 'destroy');
    });
});