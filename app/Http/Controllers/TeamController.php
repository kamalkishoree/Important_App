<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Agent;
use App\Model\Team;
use App\Model\TeamTag;
use App\Model\Tag;
use App\Model\TagsForTeam;
use App\Model\Manager;

class TeamController extends Controller
{
    protected $location_accuracy = [
        '1' => 'Level 1',
        '2' => 'Level 2',
        '3' => 'Level 3'
    ];

    protected $location_frequency = [
        '1' => '1 Minute',
        '5' => '5 Minutes',
        '15' => '15 Minutes'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agents = Agent::with(['team.manager'])->orderBy('created_at', 'DESC')->paginate(10);
        $managers = Manager::where('client_id', auth()->user()->id)->orderBy('name')->get();
        $teams  = Team::with(['manager', 'tags', 'agents'])->where('client_id', auth()->user()->id)->orderBy('created_at', 'DESC')->paginate(10);
        $tags   = TagsForTeam::all();

        $showTag = array();
        foreach ($tags as $key => $value) {
            if(!empty($value->name)){
                $showTag[] = $value->name;
            }
        }
         
        return view('team.index')->with([ 'showTag' => implode(',', $showTag),
            'agents' => $agents,
            'teams' => $teams,
            'managers' => $managers,
            'tags' => $tags,
            'location_accuracy' => $this->location_accuracy,
            'location_frequency' => $this->location_frequency
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $agents = Agent::all();
        $tags  = TagsForTeam::all();
        $tag   = [];
        foreach ($tags as $key => $value) {
            array_push($tag,$value->name);
        }

        return view('team.add-team')->with([
            'tags' => $tag,
            'agents' => $agents,
            'location_accuracy' => $this->location_accuracy,
            'location_frequency' => $this->location_frequency
        ]);
    }

    /**
     * Validation method for teams data 
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            //'manager_id' => ['required'],
            'location_accuracy' => ['required'],
            'location_frequency' => ['required']
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
        $newtag = explode(",", $request->tags);
        $tag_id = [];
        foreach ($newtag as $key => $value) {

            if(!empty($value)){
                $check = TagsForTeam::firstOrCreate(['name' => $value]);
                array_push($tag_id,$check->id);
            }
        }
        $data = [
            'name'          => $request->name,
            //'manager_id'    => $request->manager_id,
            'client_id'     => auth()->user()->id,
            'location_accuracy' => $request->location_accuracy,
            'location_frequency' => $request->location_frequency
        ];

        $team = Team::create($data);
        $team->tags()->sync($tag_id);

        if($team->wasRecentlyCreated){
            return response()->json([
                'status'=>'success',
                'message' => 'Team created Successfully!',
                'data' => $team
            ]);
        }

        //return redirect()->route('team.index')->with('success', 'Team Added successfully!');

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Team Created Successfully!',
        // ]);


        //return redirect()->back();
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
   /* public function edit($id)
    {
        $team = Team::with(['manager', 'tags'])->where('id', $id)->first();
        $agents = Agent::all();
        $tags  = TagsForTeam::all();
        $uptag   = [];
        foreach ($tags as $key => $value) {
            array_push($uptag,$value->name);
        }
        
        $teamTagIds = [];
        foreach ($team->tags as $tag) {
            $teamTagIds[] = $tag->id;
        }
        return view('team.update-team')->with([
            'team' => $team,
            'tags' => $uptag,
            'agents' => $agents,
            'teamTagIds' => $teamTagIds,
            'location_accuracy' => $this->location_accuracy,
            'location_frequency' => $this->location_frequency
        ]);
    }*/

    public function edit($id)
    {
        $team = Team::with(['tags'])->where('id', $id)->first();
        $agents = Agent::all();
        $tags  = TagsForTeam::all();
        $uptag   = [];
        foreach ($tags as $key => $value) {
            array_push($uptag,$value->name);
        }
        
        $teamTagIds = [];
        foreach ($team->tags as $tag) {
            $teamTagIds[] = $tag->name;
        }
        //print_r($teamTagIds);
        //dd($tags->toArray());
        //dd($customer->toArray());
        $returnHTML = view('team.form')->with(['team' => $team, 'tags' => $uptag, 'agents' => $agents, 'teamTagIds' => $teamTagIds, 'location_accuracy' => $this->location_accuracy, 'location_frequency' => $this->location_frequency])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * Validation method for team Update 
     */
    protected function updateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            //'manager_id' => ['required'],
            'location_accuracy' => ['required'],
            'location_frequency' => ['required']
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

        $getTeam = Team::find($id);

        $newtag = explode(",", $request->tags);

        $tag_id = [];
        foreach ($newtag as $key => $value) {

            if(!empty($value)){
                $check = TagsForTeam::firstOrCreate(['name' => $value]);
                array_push($tag_id,$check->id);
            }
        }

        $data = [
            'name'          => $request->name,
            //'manager_id'    => $request->manager_id,
            'client_id'     => auth()->user()->id,
            'location_accuracy' => $request->location_accuracy,
            'location_frequency' => $request->location_frequency
        ];
        $getTeam->tags()->sync($tag_id);
        $team = Team::where('id', $id)->update($data);

        if($team){
            return response()->json([
                'status'=>'success',
                'message' => 'Team updated Successfully!',
                'data' => $team
            ]);
        }
        //return redirect()->route('team.index')->with('success', 'Team Added successfully!');
        //return redirect()->back()->with('success', 'Team Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Team::where('id', $id)->where('client_id', auth()->user()->id)->delete();
        return redirect()->back()->with('success', 'Team deleted successfully!');
    }

    public function removeTeamAgent(Request $request, $team_id, $agent_id)
    {
        Agent::where('id', $agent_id)->update([
            'team_id' => null
        ]);
        return redirect()->back()->with('success', 'Agent removed successfully!');
    }
}
