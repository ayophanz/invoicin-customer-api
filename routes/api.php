<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

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

Route::group(['middleware' => ['auth']], function () {
    Route::controller(CustomerController::class)->group( function () {
        Route::get('customers', 'index');
        Route::post('customers/store', 'store');
        Route::put('customers/update/{id}', 'update');
        Route::delete('customers/destroy/{id}', 'destroy');
    });
});