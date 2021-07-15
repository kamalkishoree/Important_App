<?php

namespace App\Http\Controllers;

use App\Model\AgentsTag;
use App\Model\ClientPreference;
use App\Model\Geo;
use App\Model\PricePriority;
use App\Model\PricingRule;
use App\Model\TagsForAgent;
use App\Model\TagsForTeam;
use App\Model\Team;
use App\Model\TeamTag;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;

class PricingRulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pricing = PricingRule::orderBy('created_at', 'DESC')->where('id',1);
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $pricing = $pricing->orWhereHas('team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        
        $pricing = $pricing->get();

        $priority = PricePriority::where('id', 1)->first();

        $geos       = Geo::all()->pluck('name', 'id');

        $teams      = Team::OrderBy('id','asc');
        
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $teams = $teams->whereHas('permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        $teams      = $teams->get()->pluck('name', 'id');
        $team_tag   = TagsForTeam::OrderBy('id','asc');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $team_tag = $team_tag->whereHas('assignTeams.team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        
        $team_tag = $team_tag->get()->pluck('name', 'id');
        $driver_tag = TagsForAgent::all()->pluck('name', 'id');
        return view('pricing-rules.index')->with(['pricing' => $pricing, 'priority'=>$priority, 'geos' => $geos, 'teams' => $teams, 'team_tag' => $team_tag, 'driver_tag' => $driver_tag]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $geos       = Geo::all()->pluck('name', 'id');
        $teams      = Team::all()->pluck('name', 'id');
        $team_tag   = TagsForTeam::all()->pluck('name', 'id');
        $driver_tag = TagsForAgent::all()->pluck('name', 'id');
        $clientPre  = ClientPreference::where('id', 1)->with('currency')->first();
       
        return view('pricing-rules.add-pricing', compact('geos', 'teams', 'team_tag', 'driver_tag'));
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required'],
            'start_date_time' => ['required'],
            'end_date_time' => ['required']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $domain = '')
    {
        //$validator = $this->validator($request->all())->validate();
       
        $data = [
            'name'                            => $request->name,
            'start_date_time'                 => $request->start_date_time??date("Y-m-d H:i:s"),
            'end_date_time'                   => $request->end_date_time??date("Y-m-d H:i:s", strtotime('+15 years')),
            'is_default'                      => $request->is_default,
            'geo_id'                          => $request->geo_id,
            'team_id'                         => $request->team_id,
            'team_tag_id'                     => $request->team_tag_id,
            'driver_tag_id'                   => $request->driver_tag_id,
            'base_price'                      => $request->base_price,
            'base_duration'                   => $request->base_duration,
            'base_distance'                   => $request->base_distance,
            'base_waiting'                    => $request->base_waiting,
            'duration_price'                  => $request->duration_price,
            'waiting_price'                   => $request->waiting_price,
            'distance_fee'                    => $request->distance_fee,
            'cancel_fee'                      => $request->cancel_fee,
            'agent_commission_percentage'     => $request->agent_commission_percentage,
            'agent_commission_fixed'          => $request->agent_commission_fixed,
            'freelancer_commission_percentage'=> $request->freelancer_commission_percentage,
            'freelancer_commission_fixed'     => $request->freelancer_commission_fixed,
        ];
        
        $task = PricingRule::create($data);


        return redirect()->route('pricing-rules.index')->with('success', 'Pricing Rule Added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain = '', $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

    public function edit($domain = '', $id)
    {
        $pricing = PricingRule::where('id', $id)->first();
        $geos       = Geo::all()->pluck('name', 'id');
        $teams      = Team::all()->pluck('name', 'id');
        $team_tag   = TagsForTeam::all()->pluck('name', 'id');
        $driver_tag = TagsForAgent::all()->pluck('name', 'id');
        $clientPre  = ClientPreference::where('id', 1)->with('currency')->first();
        $returnHTML = view('pricing-rules.form')->with(['pricing' => $pricing, 'geos' => $geos, 'teams' => $teams, 'team_tag' => $team_tag, 'driver_tag' => $driver_tag,'client_pre'=> $clientPre])->render();

        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    {
        $getAgent = PricingRule::find($id);
        $data = [
            'name'                            => $request->name,
            'start_date_time'                 => $request->start_date_time,
            'base_price'                      => $request->base_price,
            'base_duration'                   => $request->base_duration,
            'base_distance'                   => $request->base_distance,
            //'base_waiting'                    => $request->base_waiting,
            'duration_price'                  => $request->duration_price,
            //'waiting_price'                   => $request->waiting_price,
            'distance_fee'                    => $request->distance_fee,
            'cancel_fee'                      => $request->cancel_fee,
            'agent_commission_percentage'     => $request->agent_commission_percentage,
            'agent_commission_fixed'          => $request->agent_commission_fixed,
            'freelancer_commission_percentage'=> $request->freelancer_commission_percentage,
            'freelancer_commission_fixed'     => $request->freelancer_commission_fixed,
        ];
        
        $pricing = PricingRule::where('id', $id)->update($data);
        return redirect()->route('pricing-rules.index')->with('success', 'Pricing Rule Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        $del_price_rule = PricingRule::where('id', $id);
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $del_price_rule = $del_price_rule->orWhereHas('team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        $del_price_rule = $del_price_rule->delete();

        return redirect()->back()->with('success', 'Task deleted successfully!');
    }
}
