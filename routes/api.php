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
Route::post('get-delivery-fee', 'Api\TaskController@getDeliveryFee');
Route::post('shortCode', 'Api\ShortcodeController@validateCompany');
Route::get('cmscontent','Api\ActivityController@cmsData');

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
        Route::get('cmscontent','Api\ActivityController@cmsData');
       
    });

});

Route::group([
      'middleware' => ['dbCheck', 'AppAuth']
    ], function() {
        Route::get('user', 'Api\AuthController@user');                              
        Route::get('taskList', 'Api\ActivityController@tasks');                    // api for task list
        Route::get('updateStatus', 'Api\ActivityController@updateDriverStatus');   // api for chnage driver status active ,in-active
        Route::post('updateTaskStatus', 'Api\TaskController@updateTaskStatus');    // api for chnage task status like start,cpmplate,faild
        Route::post('task/accecpt/reject', 'Api\TaskController@TaskUpdateReject'); // api for accecpt task reject task
        Route::post('agent/logs', 'Api\ActivityController@agentLog');              // api for save agent logs
        Route::get('get/profile','Api\ActivityController@profile');                // api for get agent profile
        Route::post('update/profile','Api\ActivityController@updateProfile');       // api for updateprofile
        Route::get('task/history','Api\ActivityController@taskHistory');            // api for get task history

});


Route::group([
    'middleware' => 'dbCheck','prefix' => 'public'
  ], function() {
      Route::post('task/create', 'Api\TaskController@CreateTask');
      Route::get('task/currentstatus', 'Api\TaskController@currentstatus');                              
      
});