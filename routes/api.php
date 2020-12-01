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



/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('shortCode', 'Api\ShortcodeController@validateCompany');

Route::group([
    'prefix' => 'auth'
], function () {

	Route::group([
      'middleware' => ['dbCheck', 'AppAuth']
    ], function() {
        Route::get('logout', 'Api\AuthController@logout');
    });

    Route::group([
      'middleware' => 'dbCheck'
    ], function() {
    	Route::post('sendOtp', 'Api\AuthController@sendOtp');
        Route::post('login', 'Api\AuthController@login');
    	Route::post('signup', 'Api\AuthController@signup');
    });

});

Route::group([
      'middleware' => ['dbCheck', 'AppAuth']
    ], function() {
        Route::get('user', 'Api\AuthController@user');
        Route::get('orderList', 'Api\ActivityController@orders');
        Route::get('updateStatus', 'Api\ActivityController@updateDriverStatus');
});
