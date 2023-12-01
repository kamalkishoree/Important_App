<?php

namespace App\Http\Controllers;

use App\Model\ClientPreference;
use App\Model\Team;
use App\Model\Client;
use App\Model\Timezone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Countries;
use App\Traits\googleMapApiFunctions;
use App\Traits\{GlobalFunction,  Dispatcher, DispatcherOrders};


class DashBoardController extends Controller
{
    use googleMapApiFunctions, GlobalFunction, Dispatcher, DispatcherOrders;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $agents = [];
        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();

        //setting timezone from id
        $tz = new Timezone();
        $auth->timezone = $tz->timezone_name(Auth::user()->timezone);
        $date = date('Y-m-d', time());

        $client = ClientPreference::select('id', 'map_key_1', 'dashboard_mode', 'dashboard_theme')->first();

        $googleapikey = $client->map_key_1 ?? '';
        $dashboardMode = isset($client->dashboard_mode) ? json_decode($client->dashboard_mode) : '';
        $dashboard_theme = isset($client->dashboard_theme) ? $client->dashboard_theme : 2;

        $show_dashboard_by_agent_wise = 0;
        if (!empty($dashboardMode->show_dashboard_by_agent_wise) && $dashboardMode->show_dashboard_by_agent_wise == 1) {
            $show_dashboard_by_agent_wise = 1;
        }
        $getAdminCurrentCountry = Countries::where('id', '=', Auth::user()->country_id)->first();
        if (!empty($getAdminCurrentCountry)) {
            $defaultCountryLatitude  = $getAdminCurrentCountry->latitude;
            $defaultCountryLongitude  = $getAdminCurrentCountry->longitude;
        } else {
            $defaultCountryLatitude  = '';
            $defaultCountryLongitude  = '';
        }


        if ($show_dashboard_by_agent_wise == 1) {
            $teams  = Team::get();
            // $agents  = Agent::with('agentlog')->where('is_approved',1)->get();

            $agentsData = \DB::table('agents')
                ->select('agents.*', 'latest_log.lat', 'latest_log.long', 'latest_log.device_type', 'latest_log.battery_level', 'latest_log.created_at')
                ->leftJoin('agent_logs as latest_log', function ($join) {
                    $join->on('agents.id', '=', 'latest_log.agent_id')
                        ->whereRaw('latest_log.id = (SELECT MAX(id) FROM agent_logs WHERE agent_id = agents.id)');
                })
                ->where('agents.is_approved', 1)
                ->get();

            $agents = $agentsData->map(function ($agent) {
                $agent->agentlog = [
                    'lat' => $agent->lat ?? 0,
                    'long' => $agent->long ?? 0,
                    'device_type' => $agent->device_type ?? '',
                    'battery_level' => $agent->battery_level ?? '',
                    'created_at' => $agent->created_at ?? ''
                ];
                unset($agent->lat, $agent->long);
                return (array)$agent;
            });

            // pr($agents);


        }
        $response = ['client_code' => Auth::user()->code, 'date' => $date, 'defaultCountryLongitude' => $defaultCountryLongitude, 'defaultCountryLatitude' => $defaultCountryLatitude, 'map_key' => $googleapikey, 'client_timezone' => $auth->timezone, 'searchTeams' => $teams, 'agentsData' => $agents, 'show_dashboard_by_agent_wise' => $show_dashboard_by_agent_wise, 'dashboard_theme' => $dashboard_theme];
        $teamData = $this->teamData($request);
        $response = array_merge($response, $teamData);
        $orderData = $this->orderData($request);
        $response = array_merge($response, $orderData);

        return view('dashboard.index')->with($response);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * All below functions moved to @GlobalFunction Trait
     *
     * 
     */

    public function dashboardTeamData(Request $request)
    {
        return $this->teamData($request);
    }
    public function dashboardOrderData(Request $request)
    {
        return $this->orderData($request);
    }
}
