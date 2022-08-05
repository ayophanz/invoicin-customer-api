<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerAddressController;

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
        Route::post('store', 'store');
        Route::put('update/{id}', 'update');
        Route::delete('destroy/{id}', 'destroy');
    });
    Route::controller(CustomerAddressontroller::class)->group( function () {
        Route::get('addresses', 'index');
        Route::post('addresses/store', 'store');
        Route::put('addresses/update/{id}', 'update');
        Route::delete('addresses/destroy/{id}', 'destroy');
    });
});