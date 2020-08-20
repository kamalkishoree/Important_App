<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Agent;
use App\Model\Team;
use App\Model\TeamTag;
use App\Model\Tag;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agents = Agent::with(['team.manager'])->orderBy('created_at', 'DESC')->paginate(10);
        $teams  = Team::with(['manager','tags','agents'])->where('client_id',auth()->user()->id)->orderBy('created_at','DESC')->paginate(10);
        $tags   = Tag::all();
        return view('team')->with(['agents' => $agents,'teams'=>$teams,'tags'=>$tags]);
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
     * Validation method for teams data 
    */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'manager_id' => ['required'],
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

        $data = [
            'name'          => $request->name,
            'manager_id'    => $request->manager_id,
            'client_id'     => auth()->user()->id,
            'location_accuracy'=> $request->location_accuracy,
            'location_frequency'=>$request->location_frequency    
        ];

        $team = Team::create($data);
        $team->tags()->sync($request->tags);

        return redirect()->back();
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
        $team = Team::with(['manager','tags'])->where('id',$id)->first();
        $agents = Agent::all();
        $tags  = Tag::all();

        $teamTagIds = [];
        foreach($team->tags as $tag)
        {
            $teamTagIds[] = $tag->id;
        } 
        return view('update-team')->with(['team'=>$team,'tags'=>$tags,'agents'=>$agents,'teamTagIds'=>$teamTagIds]);
    }


    /**
     * Validation method for team Update 
    */
    protected function updateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'manager_id' => ['required'],
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
        
        $data = [
            'name'          => $request->name,
            'manager_id'    => $request->manager_id,
            'client_id'     => auth()->user()->id,
            'location_accuracy'=> $request->location_accuracy,
            'location_frequency'=>$request->location_frequency    
        ];

        $getTeam->tags()->sync($request->tags);
        $team = Team::where('id', $id)->update($data);
        return redirect()->back()->with('success', 'Team Updated successfully!');
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
}
