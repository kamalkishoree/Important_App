<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;

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
Route::get('dispatch-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Auth::routes();  

Route::get('check-redis-jobs', function(){
	$connection = null;
	$default = 'default';

//For the delayed jobs
var_dump( \Queue::getRedis()->connection($connection)->zrange('queues:'.$default.':delayed' ,0, -1) );

//For the reserved jobs
var_dump( \Queue::getRedis()->connection($connection)->zrange('queues:'.$default.':reserved' ,0, -1) );
});



Route::group(['prefix' => '/godpanel','middleware' => 'CheckGodPanel'], function () {
	Route::get('/', function(){
		return view('godpanel/login');
	});
	Route::get('/login', function(){
		return view('godpanel/login');
	})->name('get.god.login');
	Route::post('login','Godpanel\LoginController@Login')->name('god.login');

	Route::middleware('auth')->group(function () {
	
		Route::any('/logout', 'Godpanel\LoginController@logout')->name('god.logout');
		Route::get('dashboard','Godpanel\DashBoardController@index')->name('god.dashboard');
		Route::resource('client','Godpanel\ClientController');
		Route::resource('language','Godpanel\LanguageController');
		Route::resource('currency','Godpanel\CurrencyController');
	});	

 });

Route::domain('{domain}')->middleware(['subdomain'])->group(function() {
	Route::group(['middleware' => ['domain','database']], function () {		

		Route::get('/signin', function(){
			return view('auth/login');
		})->name('client-login');

	
		Route::get('get-order-session','LoginController@getOrderSession')->name('setorders');

		
		Route::post('/login/client', 'LoginController@clientLogin')->name('client.login');
		Route::get('/wrong/url','LoginController@wrongurl')->name('wrong.client');

		Route::group(['middleware' => 'database'], function()
		{
			Route::get('/order/tracking/{clientcode}/{order_id}','TrackingController@OrderTracking')->name('order.tracking');
		});
		
		Route::group(['middleware' => ['auth:client'], 'prefix' => '/'], function () {
				
				Route::get('analytics','AccountingController@index')->name('accounting');
				Route::get('profileImg', 'ProfileController@displayImage');		
				Route::get('','DashBoardController@index')->name('index');
				Route::get('customize', 'ClientController@ShowPreference')->name('preference.show');
				Route::post('save/cms/{id}','ClientController@cmsSave')->name('cms.save');
				Route::post('client_preference/{id}', 'ClientController@storePreference')->name('preference');
				Route::post('task/proof','ClientController@taskProof')->name('task.proof');
				Route::get('configure', 'ClientController@ShowConfiguration')->name('configure');
				Route::post('smtp/save','ClientController@saveSmtp')->name('smtp');
				Route::get('options', 'ClientController@ShowOptions')->name('options');
				Route::resource('agent', 'AgentController');
				Route::post('pay/receive','AgentController@payreceive')->name('pay.receive');
				Route::get('agent/paydetails/{id}','AgentController@agentPayDetails')->name('agent.paydetails');
				Route::resource('customer', 'CustomerController');
				Route::get('changeStatus', 'CustomerController@changeStatus');
				Route::resource('tag', 'TagController');
				Route::get('tag/{id}/{type}/edit', 'TagController@edit')->name('tag.edit');
				Route::delete('tag/{id}/{type}', 'TagController@destroy')->name('tag.destroy');
				Route::resource('auto-allocation', 'AllocationController');
				Route::patch('auto-allocation-update/{id}', 'AllocationController@updateAllocation')->name('auto-update');
				Route::resource('profile', 'ProfileController');
				Route::resource('geo-fence', 'GeoFenceController');
				Route::get('geo-fence-all', 'GeoFenceController@allList')->name('geo.fence.list');
				Route::resource('team', 'TeamController');
				Route::delete('team-agent/{team_id}/{agent_id}','TeamController@removeTeamAgent')->name('team.agent.destroy');
				Route::resource('notifications','ClientNotificationController');
				Route::resource('pricing-rules','PricingRulesController');
				Route::post('notification_update','ClientNotificationController@updateClientNotificationEvent')->name('notification.update.client');
				Route::post('set_webhook_url','ClientNotificationController@setWebhookUrl')->name('set.webhook.url');
				Route::post('set_message','ClientNotificationController@setmessage')->name('set.message');
				Route::resource('manager', 'ManagerController');
				Route::resource('plan-billing', 'PlanBillingController');
				Route::resource('tasks','TaskController');

				Route::post('newtasks','TaskController@newtasks');

				Route::post('optimize-route','DashBoardController@optimizeRoute');
				Route::post('arrange-route','DashBoardController@arrangeRoute');
				Route::post('optimize-arrange-route','DashBoardController@optimizeArrangeRoute');
				Route::post('export-path','DashBoardController@ExportPdfPath');
				Route::post('generate-pdf','DashBoardController@generatePdf')->name('download.pdf');

				Route::post('tasks/list/{id}','TaskController@tasklist')->name('task.list');
				Route::post('search/customer', 'TaskController@search')->name('search');
				Route::post('remove-location','CustomerController@changeLocation');
				Route::post('get-tasks','DashBoardController@getTaskDetails');

				/* Store Client Information */
				Route::post('submit_client', 'UserProfile@SaveRecord')->name('store_client');
				Route::any('/logout', 'LoginController@logout')->name('client.logout');
				/* Client Profile update */
				Route::put('client/profile/{id}','ClientProfileController@update')->name('client.profile.update');
				Route::post('client/password/update','ClientProfileController@changePassword')->name('client.password.update');
				Route::get('/newdemo', function(){
					return view('extraremoved');
				});				
				
				Route::resource('subclient','SubClientController');
				Route::post('assign/agent','TaskController@assignAgent')->name('assign.agent');
				Route::post('assign/date','TaskController@assignDate')->name('assign.date');
			    Route::get('/order/feedback/{clientcode}/{order_id}','TrackingController@OrderFeedback')->name('order.feedback');
				Route::post('/feedback/save','TrackingController@SaveFeedback')->name('feedbackSave');
				Route::resource('subadmins','SubAdminController');

			  		

			
		});
		
	});
});


//feedback & tracking

Route::group(['middleware' => 'auth', 'prefix' => '/'], function () {
    Route::get('{first}/{second}/{third}', 'RoutingController@thirdLevel')->name('third');
    Route::get('{first}/{second}', 'RoutingController@secondLevel')->name('second');
    Route::get('{any}', 'RoutingController@root')->name('any');
});

