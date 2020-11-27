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

Route::get('shortCodes', 'Api\ShortcodeController@getCode');

Route::group(['middleware' => ['dbCheck','auth:api']], function() {
	Route::get('checkShortCode', 'Api\ShortcodeController@getCode1');
});

Route::group([
    'prefix' => 'auth'
], function () {


	Route::group([
      'middleware' => ['dbCheck', 'AppAuth']
    ], function() {
        Route::get('logout', 'Api\AuthController@logout');
        Route::get('user', 'Api\AuthController@user');
    });

    Route::group([
      'middleware' => 'dbCheck'
    ], function() {
    	Route::post('sendOtp', 'Api\AuthController@sendOtp');
        Route::post('login', 'Api\AuthController@login');
    	Route::post('signup', 'Api\AuthController@signup');
    });



    
  
    /*Route::group([
      
    ], function() {
        
    });*/
});
