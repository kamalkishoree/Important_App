<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

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

//switch language route
Route::get('/switch/language', function (Request $request) {
	if ($request->lang) {
		session()->put("applocale", $request->lang);
	}
	return redirect()->back();
});

Route::group(['middleware' => 'switchLanguage'], function () {
	$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
	Route::get('dispatch-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
	Route::get('show/{agent_doc}', function ($agent_doc) {
		$filename = $agent_doc->file_name;
		$path = storage_path($filename);

		return Response::make($imgproxyurl . Storage::disk('s3')->url($filename), 200, [
			'Content-Type' => 'application/pdf',
			'Content-Disposition' => 'inline; filename="' . $filename . '"'
		]);
	});

	Route::get('/howto/signup', function () {
		return view('How-to-SignUp-in-Royo-Dispatcher');
	});
	Route::get('terms_n_condition', 'CMSScreenController@terms_n_condition');
	Route::get('privacy_policy', 'CMSScreenController@privacy_policy');

	Auth::routes();

	Route::get('check-redis-jobs', function () {
		$connection = null;
		$default = 'default';

		//For the delayed jobs
		print_r("For the delayed jobs");
		print_r("<pre>");
		print_r(\Queue::getRedis()->connection($connection)->zrange('queues:' . $default . ':delayed', 0, -1));
		print_r("</pre>");
		//For the reserved jobs
		print_r("For the reserved jobs");
		print_r("<pre>");
		var_dump(\Queue::getRedis()->connection($connection)->zrange('queues:' . $default . ':reserved', 0, -1));
		print_r("</pre>");
	});



	Route::group(['prefix' => '/godpanel', 'middleware' => 'CheckGodPanel'], function () {
		Route::get('/', function () {
			return view('godpanel/login');
		});
		Route::get('/login', function () {
			return view('godpanel/login');
		})->name('get.god.login');
		Route::post('login', 'Godpanel\LoginController@Login')->name('god.login');

		Route::middleware('auth')->group(function () {

			Route::any('/logout', 'Godpanel\LoginController@logout')->name('god.logout');
			Route::get('dashboard', 'Godpanel\DashBoardController@index')->name('god.dashboard');
			Route::resource('client', 'Godpanel\ClientController');
			Route::resource('language', 'Godpanel\LanguageController');
			Route::resource('currency', 'Godpanel\CurrencyController');
		});
	});

	Route::domain('{domain}')->middleware(['subdomain'])->group(function () {
		Route::group(['middleware' => ['domain', 'database']], function () {

			Route::get('/signin', function () {
				return view('auth/login');
			})->name('client-login');
			Route::get('get-order-session', 'LoginController@getOrderSession')->name('setorders');
		});

		Route::get('/demo/page', function () {
			return view('demo');
		});

		Route::post('/login/client', 'LoginController@clientLogin')->name('client.login');
		Route::get('/wrong/url', 'LoginController@wrongurl')->name('wrong.client');
		Route::group(['middleware' => 'database'], function () {
			Route::get('/order/tracking/{clientcode}/{order_id}', 'TrackingController@OrderTracking')->name('order.tracking');
			Route::get('/order-details/tracking/{clientcode}/{order_id}', 'TrackingController@OrderTrackingDetail')->name('order.tracking.detail');
			Route::get('/order-cancel/tracking/{clientcode}/{order_id}', 'TrackingController@orderCancelFromOrder')->name('order.cancel.from_order');

		});

		Route::group(['middleware' => ['auth:client'], 'prefix' => '/'], function () {

            Route::get('notifi', 'AgentController@test_notification');
			Route::get('agent/filter', 'AgentController@agentFilter');
			Route::get('agent/export', 'AgentController@export')->name('agents.export');
			Route::get('customer/filter', 'CustomerController@customerFilter');
			Route::get('customer/export', 'CustomerController@customersExport')->name('customer.export');
			Route::get('task/export', 'TaskController@tasksExport')->name('task.export');
			Route::get('task/filter', 'TaskController@taskFilter');
			Route::get('analytics', 'AccountingController@index')->name('accounting');
			Route::get('profileImg', 'ProfileController@displayImage');
			Route::get('', 'DashBoardController@index')->name('index');
			Route::get('customize', 'ClientController@ShowPreference')->name('preference.show');
			Route::post('save/cms/{id}', 'ClientController@cmsSave')->name('cms.save');
			Route::post('client_preference/{id}', 'ClientController@storePreference')->name('preference');
			Route::post('route-create-configure/{id}', 'ClientController@routeCreateConfigure')->name('route.create.configure');
			Route::post('task/proof', 'ClientController@taskProof')->name('task.proof');
			Route::get('configure', 'ClientController@ShowConfiguration')->name('configure');
			Route::post('smtp/save', 'ClientController@saveSmtp')->name('smtp');
            Route::post('fivcon/save', 'ClientController@faviconUoload')->name('favicon');
			Route::get('options', 'ClientController@ShowOptions')->name('options');
			Route::resource('agent', 'AgentController');
			Route::get('agent/{id}/show', 'AgentController@show')->name('agent.show');
			Route::post('pay/receive', 'AgentController@payreceive')->name('pay.receive');
			Route::get('agent/paydetails/{id}', 'AgentController@agentPayDetails')->name('agent.paydetails');
			Route::post('agent/approval_status', 'AgentController@approval_status')->name('agent/approval_status');
			Route::get('agent/payout/requests', 'AgentPayoutController@agentPayoutRequests')->name('agent.payout.requests');
			Route::get('agent/payout/requests/export', 'AgentPayoutController@export')->name('agents.payout.requests.export');
			Route::get('agent/payout/requests/filter', 'AgentPayoutController@agentPayoutRequestsFilter')->name('agent.payout.requests.filter');
        	Route::post('agent/payout/request/complete/{id}', 'AgentPayoutController@agentPayoutRequestComplete')->name('agent.payout.request.complete');
			Route::post('agent/payout/requests/complete/all', 'AgentPayoutController@agentPayoutRequestsCompleteAll')->name('agent.payout.requests.complete.all');
			Route::post('agent/payout/bank/details', 'AgentPayoutController@agentPayoutBankDetails')->name('agent.payout.bank.details');
			Route::post('agent/change_approval_status', 'AgentController@change_approval_status')->name('agent/change_approval_status');
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
			Route::delete('team-agent/{team_id}/{agent_id}', 'TeamController@removeTeamAgent')->name('team.agent.destroy');
			Route::resource('notifications', 'ClientNotificationController');
			Route::resource('pricing-rules', 'PricingRulesController');
			Route::post('notification_update', 'ClientNotificationController@updateClientNotificationEvent')->name('notification.update.client');
			Route::post('set_webhook_url', 'ClientNotificationController@setWebhookUrl')->name('set.webhook.url');
			Route::post('set_message', 'ClientNotificationController@setmessage')->name('set.message');
			Route::resource('manager', 'ManagerController');
			Route::resource('plan-billing', 'PlanBillingController');
			Route::resource('tasks', 'TaskController');

			Route::post('newtasks', 'TaskController@newtasks');
			Route::any('updatetasks/tasks/{id}', 'TaskController@update');
			Route::post('single_taskdelete', 'TaskController@deleteSingleTask')->name('tasks.single.destroy');

			Route::post('optimize-route', 'DashBoardController@optimizeRoute');
			Route::post('arrange-route', 'DashBoardController@arrangeRoute');
			Route::post('optimize-arrange-route', 'DashBoardController@optimizeArrangeRoute');
			Route::post('export-path', 'DashBoardController@ExportPdfPath');
			Route::post('generate-pdf', 'DashBoardController@generatePdf')->name('download.pdf');

			Route::post('tasks/list/{id}', 'TaskController@tasklist')->name('task.list');
			Route::post('search/customer', 'TaskController@search')->name('search');
			Route::post('remove-location', 'CustomerController@changeLocation');
			Route::post('get-tasks', 'DashBoardController@getTaskDetails');

			/* Store Client Information */
			Route::post('submit_client', 'UserProfile@SaveRecord')->name('store_client');
			Route::any('/logout', 'LoginController@logout')->name('client.logout');
			/* Client Profile update */
			Route::put('client/profile/{id}', 'ClientProfileController@update')->name('client.profile.update');
			Route::post('client/password/update', 'ClientProfileController@changePassword')->name('client.password.update');
			Route::get('/newdemo', function () {
				return view('extraremoved');
			});

			Route::resource('subclient', 'SubClientController');
			Route::post('assign/agent', 'TaskController@assignAgent')->name('assign.agent');
			Route::post('assign/date', 'TaskController@assignDate')->name('assign.date');
			Route::get('/order/feedback/{clientcode}/{order_id}', 'TrackingController@OrderFeedback')->name('order.feedback');
			Route::post('/feedback/save', 'TrackingController@SaveFeedback')->name('feedbackSave');
			Route::resource('subadmins', 'SubAdminController');


			// Route::get('/order/tracking/{clientcode}/{order_id}','TrackingController@OrderTracking')->name('order.tracking');

			Route::get('/order/feedback/{clientcode}/{order_id}', 'TrackingController@OrderFeedback')->name('order.feedback');

			Route::post('/feedback/save', 'TrackingController@SaveFeedback')->name('feedbackSave');

			//for testing
			//Route::get('testing','DashBoardController@ExportPdfPath');
			//Route::get('testing','DashBoardController@GetRouteDirection');


			Route::get('demo/page', 'GeoFenceController@newDemo')->name('new.demo');

			Route::resource('payoption', 'PaymentOptionController');
			Route::post('updateAll', 'PaymentOptionController@updateAll')->name('payoption.updateAll');
			Route::post('payoutUpdateAll', 'PaymentOptionController@payoutUpdateAll')->name('payoutOption.payoutUpdateAll');
		});
	});



	//feedback & tracking

	Route::group(['middleware' => 'auth', 'prefix' => '/'], function () {
		Route::get('{first}/{second}/{third}', 'RoutingController@thirdLevel')->name('third');
		Route::get('{first}/{second}', 'RoutingController@secondLevel')->name('second');
		Route::get('{any}', 'RoutingController@root')->name('any');
	});

	Route::get('driver/registration/document/edit', 'ClientController@show')->name('driver.registration.document.edit');
	Route::post('driverregistrationdocument/create', 'ClientController@store')->name('driver.registration.document.create');
	Route::post('driverregistrationdocument/update', 'ClientController@update')->name('driver.registration.document.update');
	Route::post('driver/registration/document/delete', 'ClientController@destroy')->name('driver.registration.document.delete');
 
 

});
