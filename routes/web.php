<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => '/Godpanel'], function () {
	Route::get('login', function(){
		return view('godpanel/login');
	});
	Route::post('login','Godpanel\LoginController@Login')->name('god.login');
	Route::get('dashboard','Godpanel\DashBoardController@Dashboard')->name('god.dashboard');
	Route::resource('client', 'ClientController');

});

Auth::routes();
Route::group(['middleware' => 'auth', 'prefix' => '/'], function () {
Route::get('', function(){
	return view('dashboard');
})->name('index');
Route::resource('client', 'ClientController');
Route::get('customize', 'ClientController@ShowPreference')->name('preference.show');
Route::post('client_preference/{id}', 'ClientController@storePreference')->name('preference');
Route::get('configure', 'ClientController@ShowConfiguration')->name('configure');
Route::get('options', 'ClientController@ShowOptions')->name('options');

Route::resource('agent', 'AgentController');
Route::resource('customer', 'CustomerController');
Route::resource('tag', 'TagController');
Route::get('tag/{id}/{type}/edit', 'TagController@edit')->name('tag.edit');
Route::delete('tag/{id}/{type}', 'TagController@destroy')->name('tag.destroy');
Route::resource('auto-allocation', 'AllocationController');
Route::resource('profile', 'ProfileController');
Route::resource('geo-fence', 'GeoFenceController');
Route::get('geo-fence-all', 'GeoFenceController@allList')->name('geo.fence.list');
Route::resource('team', 'TeamController');
Route::delete('team-agent/{team_id}/{agent_id}','TeamController@removeTeamAgent')->name('team.agent.destroy');
Route::resource('notifications','ClientNotificationController');
Route::post('notification_update','ClientNotificationController@updateClientNotificationEvent')->name('notification.update.client');
Route::post('set_webhook_url','ClientNotificationController@setWebhookUrl')->name('set.webhook.url');
Route::resource('manager', 'ManagerController');

Route::get('{first}/{second}/{third}', 'RoutingController@thirdLevel')->name('third');
Route::get('{first}/{second}', 'RoutingController@secondLevel')->name('second');
Route::get('{any}', 'RoutingController@root')->name('any');

/* Store Client Information */
Route::post('submit_client', 'UserProfile@SaveRecord')->name('store_client');


});







// Route::group(['middleware' => 'auth', 'prefix' => '/'], function () {
//     Route::get('{first}/{second}/{third}', 'RoutingController@thirdLevel')->name('third');
//     Route::get('{first}/{second}', 'RoutingController@secondLevel')->name('second');
//     Route::get('{any}', 'RoutingController@root')->name('any');
// });

// landing
// Route::get('', 'RoutingController@index')->name('index');