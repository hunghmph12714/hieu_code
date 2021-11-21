<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Users
Route::prefix('/user')->group( function(){
    Route::post('/login', 'api\v1\LoginController@login');
    Route::post('/verify-phone', 'api\v1\LoginController@verifyPhone');    
    Route::post('/cooldown-otp', 'api\v1\LoginController@checkCooldown');
    Route::post('/verify-otp', 'api\v1\LoginController@verifyOtp');
    Route::post('/logout','api\v1\LoginController@logoutApi');
    Route::group(['middleware' => ['auth:api']], function() {
        Route::get('/all', 'api\v1\user\UserController@index');
        Route::get('/event/{parent_id}', 'api\v1\events\EventController@getEvent');
    });
});
Route::group(['middleware' => ['auth:api']], function() {
    Route::get('/event/{parent_id}', 'api\v1\events\EventController@getEvent');
});
Route::get('/validate-token', 'api\v1\LoginController@checkAuth')->middleware('auth:api');