<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Agent;
use App\Model\Team;
use App\Model\Geo;
use App\Model\DriverGeo;
use Auth;

class GeoFenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::with(['agents'])->where('client_id',auth()->user()->id)->orderBy('name')->get();
        $agents= Agent::whereIn('team_id',function($q){
            $q->select('id')->from('teams')->where('client_id',Auth::user()->id);
        })->get();

        return view('geo-fence')->with([
            'teams' =>  $teams,
            'agents'=>  $agents
        ]);
    }

    public function allList(){
        $all_coordinates = [];
        $geos = Geo::where('client_id',auth()->user()->id)->orderBy('created_at', 'DESC')->get();
        foreach($geos as $k=>$v){
            $all_coordinates[] =[
                'name' => 'abc',
                'coordinates' => $v->geo_coordinates
            ]; 
        }

        $center = [
            'lat' => 30.0612323,
            'lng' => 76.1239239
        ];

        if(!empty($all_coordinates)){
            $center['lat'] = $all_coordinates[0]['coordinates'][0]['lat'];
            $center['lng'] = $all_coordinates[0]['coordinates'][0]['lng'];
        }

        return view('geo-fence-list')->with(['geos' => $geos,'all_coordinates'=>$all_coordinates,'center'=>$center]);
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
     * Validation method for geo-fence data 
    */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'zoom_level' => ['required'],
            'agents' => ['required']
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all())->validate();

        $data = [
            'name'          => $request->name,
            'description'   => $request->description,
            'zoom_level'    => $request->zoom_level,
            'geo_array'     => $request->latlongs,
            'client_id'     => auth()->user()->id
        ];

        $geo = Geo::create($data);

        $geo->agents()->sync($request->agents);

        //update team_id if any provided //
        if($request->team_id){
            DriverGeo::where('geo_id',$geo->id)->update([
                'team_id' => $request->team_id 
            ]);
        }

        return redirect()->route('geo.fence.list')->with('success', 'Added successfully!');
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
        $geo = Geo::with(['agents'])->where('id',$id)->first();
        $teams = Team::with(['agents'])->where('client_id',auth()->user()->id)->orderBy('name')->get();
        $agents= Agent::whereIn('team_id',function($q){
            $q->select('id')->from('teams')->where('client_id',Auth::user()->id);
        })->get();

        return view('update-geo-fence')->with([
            'geo'=>$geo,
            'agents'=>$agents,
            'teams'=>$teams
            ]);
    }


    protected function updateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255']
        ]);
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
        $validator = $this->updateValidator($request->all())->validate();

        $geo = Geo::find($id);

        $data = [
            'name'          => $request->name,
            'description'   => $request->description
        ];

        $updated = Geo::where('id', $id)->update($data);

        $geo->agents()->sync($request->agents);

        return redirect()->back()->with('success', 'Updated successfully!');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Geo::where('id',$id)->where('client_id',auth()->user()->id)->delete();
        return redirect()->back()->with('success', 'Updated successfully!');
    }
}
