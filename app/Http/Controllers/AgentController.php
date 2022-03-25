<?php

namespace App\Http\Controllers;

use DB;
use Excel;
use Exception;
use DataTables;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use GuzzleHttp\Client as GCLIENT;
use App\Traits\ApiResponser;
use App\Exports\AgentsExport;
use Doctrine\DBAL\Driver\DrizzlePDOMySql\Driver;
use App\Model\{Agent, AgentDocs, AgentPayment, DriverGeo, Order, Otp, Team, TagsForAgent, TagsForTeam, Countries, Client, ClientPreferences, DriverRegistrationDocument, Geo, Timezone};
use Kawankoding\Fcm\Fcm;
class AgentController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function test_notification(Request $request){
        $new[] =$request->tokon ?? 'elatojS0SVuKg_qljDzRFb:APA91bEMxlpN2VPkrGaPw7MMOIaRweblEJP9Ff1K1Yd82VBeCSVHCpqmzffWj9C-_1ouvlYvPYTXCj3sKg9iUXl2XZNXcOnx1xrNXRsqgMMqdubH5yoKRETDuqo5qDc6_vt-4X1YgZjT';
        $client_preferences = getClientPreferenceDetail();
        $fcm_server_key = !empty($client_preferences->fcm_server_key)? $client_preferences->fcm_server_key : config('laravel-fcm.server_key');
        $item['title']     = 'Pickup Request';
        $item['body']      = 'Check All Details For This Request In App';
        $fcmObj = new Fcm($fcm_server_key);
        $fcm_store = $fcmObj->to($new) // $recipients must an array
                        ->priority('high')
                        ->timeToLive(0)
                        ->data($item)
                        ->notification([
                            'title'              => 'Pickup Request',
                            'body'               => 'Check All Details For This Request In App',
                            'sound'              => 'notification.mp3',
                            'android_channel_id' => 'Royo-Delivery',
                            'soundPlay'          => true,
                            'show_in_foreground' => true,
                        ])
                        ->send();
                        echo ($new[0]);
                        pr($fcm_store);
    }
    public function index(Request $request)
    {
        $agents = Agent::orderBy('id', 'DESC');
        // if (!empty($request->date)) {
        //     $agents->whereBetween('created_at', [$request->date . " 00:00:00", $request->date . " 23:59:59"]);
        // }

        $user = Auth::user();

        if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
            $agents = $agents->whereHas('team.permissionToManager', function ($query) use($user) {
                $query->where('sub_admin_id', $user->id);
            });
        }
        $agents = $agents->get();


        $tags  = TagsForAgent::all();
        $tag   = [];
        foreach ($tags as $key => $value) {
            array_push($tag, $value->name);
        }
        $teams  = Team::where('client_id', auth()->user()->code)->orderBy('name');
        if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
            $teams = $teams->whereHas('permissionToManager', function ($query) use($user) {
                $query->where('sub_admin_id', $user->id);
            });
        }

        $teams        = $teams->get();
        $selectedDate = !empty($request->date) ? $request->date : '';
        // $tags         = TagsForTeam::all();

        $getAdminCurrentCountry = Countries::where('id', '=', $user->country_id)->get()->first();
        if (!empty($getAdminCurrentCountry)) {
            $countryCode = $getAdminCurrentCountry->code;
        } else {
            $countryCode = '';
        }

        // getting all geo fence list to filter agents
        $geos = Geo::where('client_id', $user->code)->orderBy('created_at', 'DESC')->get();

        $agentsCount       = count($agents);
        $employeesCount    = count($agents->where('type', 'Employee'));
        $freelancerCount   = count($agents->where('type', 'Freelancer'));
        $agentActive       = count($agents->where('is_activated', 1));
        $agentInActive     = count($agents->where('is_activated', 0));
        $agentIsAvailable  = count($agents->where('is_available', 1));
        $agentNotAvailable = count($agents->where('is_available', 0));
        $agentIsApproved   = count($agents->where('is_approved', 1));
        $agentNotApproved  = count($agents->where('is_approved', 0));
        $agentRejected   = count($agents->where('is_approved', 2));
        $driver_registration_documents = DriverRegistrationDocument::get();

        return view('agent.index')->with(['agents' => $agents, 'geos' => $geos, 'driver_registration_documents' => $driver_registration_documents, 'agentIsAvailable' => $agentIsAvailable, 'agentNotAvailable' => $agentNotAvailable, 'agentIsApproved' => $agentIsApproved, 'agentNotApproved' => $agentNotApproved, 'agentsCount' => $agentsCount, 'employeesCount' => $employeesCount, 'agentActive' => $agentActive, 'agentInActive' => $agentInActive, 'freelancerCount' => $freelancerCount, 'teams' => $teams, 'tags' => $tags, 'selectedCountryCode' => $countryCode, 'calenderSelectedDate' => $selectedDate, 'showTag' => implode(',', $tag), 'agentRejected' => $agentRejected]);
    }

    public function agentFilter(Request $request)
    {
        try {
            $tz = new Timezone();
            $user = Auth::user();
            $client = Client::where('code', $user->code)->with(['getTimezone', 'getPreference'])->first();
            $client_timezone = $client->getTimezone ? $client->getTimezone->timezone : 251;
            $timezone = $tz->timezone_name($client_timezone);
            $agents = Agent::orderBy('id', 'DESC');
            if (!empty($request->get('date_filter'))) {
                $dateFilter = explode('to', $request->get('date_filter'));
                if (count($dateFilter) > 1) {
                    $agents->whereBetween('created_at', [trim($dateFilter[0]) . " 00:00:00", trim($dateFilter[1]) . " 23:59:59"]);
                } else {
                    $agents->whereBetween('created_at', [trim($dateFilter[0]) . " 00:00:00", trim($dateFilter[0]) . " 23:59:59"]);
                }
            }
            if (!empty($request->get('geo_filter'))) {
                $geo_id = $request->get('geo_filter');
                $agents->whereHas('geoFence', function($q) use($geo_id){
                    $q->where('geo_id', $geo_id);
                });
            }
            if (!empty($request->get('tag_filter'))) {
                $tag_id = $request->get('tag_filter');
                $agents->whereHas('tags', function($q) use($tag_id){
                    $q->where('tag_id', $tag_id);
                });
            }
            if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
                $agents = $agents->whereHas('team.permissionToManager', function ($query) use($user) {
                    $query->where('sub_admin_id', $user->id);
                });
            }

            $agents = $agents->where('is_approved', $request->status)->orderBy('id', 'desc');
            return Datatables::of($agents)
                ->editColumn('name', function ($agents) use ($request) {
                    $name =$agents->name;
                    return $name;
                })
                ->editColumn('profile_picture', function ($agents) use ($request) {
                    $src = (isset($agents->profile_picture) ? $request->imgproxyurl . Storage::disk('s3')->url($agents->profile_picture) : Phumbor::url(URL::to('/asset/images/no-image.png')));
                    return $src;
                })
                ->editColumn('team', function ($agents) use ($request) {
                    $team = (isset($agents->team->name) ? $agents->team->name : __('Team Not Alloted'));
                    return $team;
                })
                ->editColumn('vehicle_type_id', function ($agents) use ($request) {
                    $src = asset('assets/icons/extra/' . $agents->vehicle_type_id . '.png');
                    return $src;
                })
                ->editColumn('type', function ($agents) use ($request) {
                    return __($agents->type);
                })
                ->editColumn('cash_to_be_collected', function ($agents) use ($request) {
                    $cash = $agents->order->sum('cash_to_be_collected');
                    return number_format((float)$cash, 2, '.', '');
                })
                ->editColumn('driver_cost', function ($agents) use ($request) {
                    $orders = $agents->order->sum('driver_cost');
                    return number_format((float)$orders, 2, '.', '');
                })
                ->editColumn('cr', function ($agents) use ($request) {
                    $receive = $agents->agentPayment->sum('cr');
                    return number_format((float)$receive, 2, '.', '');
                })
                ->editColumn('dr', function ($agents) use ($request) {
                    $pay = $agents->agentPayment->sum('dr');
                    return number_format((float)$pay, 2, '.', '');
                })
                ->editColumn('pay_to_driver', function ($agents) use ($request) {
                    $cash        = $agents->order->sum('cash_to_be_collected');
                    $orders      = $agents->order->sum('driver_cost');
                    $receive     = $agents->agentPayment->sum('cr');
                    $pay         = $agents->agentPayment->sum('dr');
                    $payToDriver = $agents->balanceFloat + ($pay - $receive) - ($cash - $orders);
                    return number_format((float)$payToDriver, 2, '.', '');
                })
                ->editColumn('created_at', function ($agents) use ($request, $timezone) {
                    return convertDateTimeInTimeZone($agents->created_at, $timezone);
                })
                ->editColumn('updated_at', function ($agents) use ($request, $timezone) {
                    return convertDateTimeInTimeZone($agents->updated_at, $timezone);
                })
                ->editColumn('action', function ($agents) use ($request) {
                    if($request->status == 1){
                        $approve_action = '<div class="inner-div agent_approval_button" data-agent_id="'.$agents->id.'" data-status="2" title="Reject"><i class="fa fa-user-times" style="color: red; cursor:pointer;"></i></div>';
                    } else if($request->status == 0){
                        $approve_action = '<div class="inner-div agent_approval_button" data-agent_id="'.$agents->id.'" data-status="1" title="Approve"><i class="fas fa-user-check" style="color: green; cursor:pointer;"></i></div><div class="inner-div agent_approval_button" data-agent_id="'.$agents->id.'" data-status="2" title="Reject"><i class="fa fa-user-times" style="color: red; cursor:pointer;"></i></div>';
                    } else if($request->status == 2){
                        $approve_action = '<div class="inner-div agent_approval_button" data-agent_id="'.$agents->id.'" data-status="1" title="Approve"><i class="fas fa-user-check" style="color: green; cursor:pointer;"></i></div>';
                    }
                    $action = '<div class="form-ul">'.$approve_action.'
                                    <div class="inner-div"> <a href="' . route('agent.show', $agents->id) . '" class="action-icon viewIcon" agentId="' . $agents->id . '"> <i class="fa fa-eye"></i></a></div>
                                    <div class="inner-div"> <a href="' . route('agent.edit', $agents->id) . '" class="action-icon editIcon" agentId="' . $agents->id . '"> <i class="mdi mdi-square-edit-outline"></i></a></div>
                                    <div class="inner-div">
                                        <form id="agentdelete'.$agents->id.'" method="POST" action="' . route('agent.destroy', $agents->id) . '">
                                            <input type="hidden" name="_token" value="' . csrf_token() . '" />
                                            <input type="hidden" name="_method" value="DELETE">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary-outline action-icon"> <i class="mdi mdi-delete" agentid="'.$agents->id.'"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>';
                    return $action;
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        // $instance->collection = $instance->collection->filter(function ($row) use ($request){
                        //     if (!empty($row['uid']) && Str::contains(Str::lower($row['uid']), Str::lower($request->get('search')))){
                        //         return true;
                        //     }elseif (!empty($row['phone_number']) && Str::contains(Str::lower($row['phone_number']), Str::lower($request->get('search')))){
                        //         return true;
                        //     }else if (!empty($row['name']) && Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))) {
                        //         return true;
                        //     }else if (!empty($row['type']) && Str::contains(Str::lower($row['type']), Str::lower($request->get('search')))) {
                        //         return true;
                        //     }else if (!empty($row['team']) && Str::contains(Str::lower($row['team']), Str::lower($request->get('search')))) {
                        //         return true;
                        //     }else if (!empty($row['created_at']) && Str::contains(Str::lower($row['created_at']), Str::lower($request->get('search')))) {
                        //         return true;
                        //     }
                        //     return false;
                        // });

                        $search = $request->get('search');
                        $instance->where('uid', 'Like', '%'.$search.'%')
                            ->orWhere('name', 'Like', '%'.$search.'%')
                            ->orWhere('phone_number', 'Like', '%'.$search.'%')
                            ->orWhere('type', 'Like', '%'.$search.'%')
                            ->orWhere('created_at', 'Like', '%'.$search.'%')
                            ->orWhereHas('team', function($q) use($search){
                                $q->where('name', 'Like', '%'.$search.'%');
                            });
                    }
                }, true)
                ->make(true);
        } catch (Exception $e) {
        }
    }

    public function export()
    {
        return Excel::download(new AgentsExport, 'agents.xlsx');
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
     * Validation method for agents data
     */
    protected function validator(array $data)
    {
        $full_number = '';
        if (isset($data['country_code']) && !empty($data['country_code']) && isset($data['phone_number']) && !empty($data['phone_number']))
            $full_number = '+' . $data['country_code'] . $data['phone_number'];

        $data['phone_number'] = '+' . $data['country_code'] . $data['phone_number'];
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required'],
            // 'vehicle_type_id' => ['required'],
            'team_id' => ['required'],
            //'make_model' => ['required'],
            //'plate_number' => ['required'],
            'phone_number' =>  ['required', 'min:9', 'max:15', Rule::unique('agents')->where(function ($query) use ($full_number) {
                return $query->where('phone_number', $full_number);
            })],
            //'color' => ['required'],
            'profile_picture' => ['mimes:jpeg,png,jpg,gif,svg|max:2048'],
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
        $validator = $this->validator($request->all())->validate();
        $getFileName = null;
        // $full_number = '+'.$request->country_code.$request->phone_number;
        // if(isset($full_number) && !empty($full_number)){
        //     $already = Agent::where('phone_number',$full_number)->count();
        //     if($already > 0){
        //         return response()->json([
        //             'status'=>'error',
        //             'message' => 'The Phone number is already exist!',
        //             'data' => []
        //         ]);
        //     }

        // }



        $newtag = explode(",", $request->tags);
        $tag_id = [];
        foreach ($newtag as $key => $value) {
            if (!empty($value)) {
                $check = TagsForAgent::firstOrCreate(['name' => $value]);
                array_push($tag_id, $check->id);
            }
        }

        // Handle File Upload
        if ($request->hasFile('profile_picture')) {
            $folder = str_pad(Auth::user()->code, 8, '0', STR_PAD_LEFT);
            $folder = 'client_' . $folder;
            $file = $request->file('profile_picture');
            $file_name = uniqid() . '.' .  $file->getClientOriginalExtension();
            $s3filePath = '/assets/' . $folder . '/agents' . $file_name;
            $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
            $getFileName = $path;
        }




        $data = [
            'name' => $request->name,
            'team_id' => $request->team_id == null ? $team_id = null : $request->team_id,
            'type' => $request->type,
            'vehicle_type_id' => $request->vehicle_type_id ?? null,
            'make_model' => $request->make_model,
            'type' => $request->type,
            'make_model' => $request->make_model ?? null,
            'plate_number' => $request->plate_number ?? null,
            'phone_number' => '+' . $request->country_code . $request->phone_number,
            'color' => $request->color ?? null,
            'profile_picture' => $getFileName != null ? $getFileName : 'assets/client_00000051/agents5fedb209f1eea.jpeg/Ec9WxFN1qAgIGdU2lCcatJN5F8UuFMyQvvb4Byar.jpg',
            'uid' => $request->uid ?? null,
            'is_approved' => 1
        ];

        $agent = Agent::create($data);
        $agent->tags()->sync($tag_id);

        $driver_registration_documents = DriverRegistrationDocument::get();
        foreach ($driver_registration_documents as $driver_registration_document) {
            $agent_docs = new AgentDocs();
            $name = str_replace(" ", "_", $driver_registration_document->name);
            if ($driver_registration_document->file_type != "Text") {
                if ($request->hasFile($name)) {
                    $folder = str_pad(Auth::user()->code, 8, '0', STR_PAD_LEFT);
                    $folder = 'client_' . $folder;
                    $file = $request->file($name);
                    $file_name = uniqid() . '.' . $file->getClientOriginalExtension();
                    $s3filePath = '/assets/' . $folder . '/agents' . $file_name;
                    $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
                    $getFileName = $path;
                }
                $agent_docs->file_name = $getFileName;
            } else {
                $agent_docs->file_name = $request->$name;
            }
            $agent_docs->agent_id = $agent->id;
            $agent_docs->file_type = $driver_registration_document->file_type;
            $agent_docs->label_name = $driver_registration_document->name;
            $agent_docs->save();
        }

        if ($agent->wasRecentlyCreated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Thanks for signing up. We will get back to you shortly!',
                'data' => $agent
            ]);
        }
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

        $agent = Agent::with(['tags'])->where('id', $id)->first();

        $teams = Team::where('client_id', auth()->user()->code);
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $teams = $teams->whereHas('permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }

        $teams = $teams->get();

        $tags  = TagsForAgent::all();
        $uptag   = [];
        foreach ($tags as $key => $value) {
            array_push($uptag, $value->name);
        }

        $tagIds = [];
        $returnHTML = '';
        if (!empty($agent)) {
            foreach ($agent->tags as $tag) {
                $tagIds[] = $tag->name;
            }
            $date = Date('Y-m-d H:i:s');

            $otp = Otp::where('phone', $agent->phone_number)->where('valid_till', '>=', $date)->first();
            if (isset($otp)) {
                $send_otp = $otp->opt;
            } else {
                $send_otp = __('View OTP after Logging in the Driver App');
            }
            $agents_docs = AgentDocs::where('agent_id', $id)->get();
            $driver_registration_documents = DriverRegistrationDocument::get();

            $returnHTML = view('agent.form-show')->with(['agent' => $agent, 'driver_registration_documents' => $driver_registration_documents, 'teams' => $teams, 'tags' => $uptag, 'agent_docs' => $agents_docs, 'tagIds' => $tagIds, 'otp' => $send_otp])->render();
        }
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function edit($domain = '', $id)
    {
        $agent = Agent::with(['tags'])->where('id', $id)->first();
        $teams = Team::where('client_id', auth()->user()->code);
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $teams = $teams->whereHas('permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        $teams = $teams->get();

        $tags  = TagsForAgent::all();
        $uptag   = [];
        foreach ($tags as $key => $value) {
            array_push($uptag, $value->name);
        }

        $tagIds = [];
        foreach ($agent->tags as $tag) {
            $tagIds[] = $tag->name;
        }
        $date = Date('Y-m-d H:i:s');

        $otp = Otp::where('phone', $agent->phone_number)->where('valid_till', '>=', $date)->first();
        if (isset($otp)) {
            $send_otp = $otp->opt;
        } else {
            $send_otp = __('View OTP after Logging in the Driver App');
        }

        $agents_docs = AgentDocs::where('agent_id', $id)->get();
        $driver_registration_documents = DriverRegistrationDocument::get();

        $returnHTML = view('agent.form')->with(['agent' => $agent, 'teams' => $teams, 'tags' => $uptag, 'tagIds' => $tagIds, 'otp' => $send_otp, 'driver_registration_documents' => $driver_registration_documents, 'agent_docs' => $agents_docs])->render();

        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Validation method for agent Update
     */
    protected function updateValidator(array $data, $id)
    {
        $full_number = '';
        $full_number = $data['phone_number'];

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required'],
            // 'vehicle_type_id' => ['required'],
            'team_id' => ['required'],
            //'make_model' => ['required'],
            //'plate_number' => ['required'],
            'phone_number' => ['required', 'min:9', 'max:15', Rule::unique('agents')->where(function ($query) use ($full_number, $id) {
                return $query->where('phone_number', $full_number)->where('id', '!=', $id);
            })],
            //'color' => ['required'],
            'profile_picture' => ['mimes:jpeg,png,jpg,gif,svg|max:2048'],
        ]);
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
        $validator = $this->updateValidator($request->all(), $id)->validate();

        $agent = Agent::findOrFail($id);
        // if(isset($request->phone_number) && !empty($request->phone_number)){
        //     $already = Agent::where('phone_number',$request->phone_number)->where('id','!=',$id)->count();
        //     if($already > 0){
        //         return response()->json([
        //             'status'=>'error',
        //             'message' => 'The Phone number is already exist!',
        //             'data' => []
        //         ]);
        //     }

        // }
        $getFileName = $agent->profile_picture;

        $newtag = explode(",", $request->tags);

        $tag_id = [];

        foreach ($newtag as $key => $value) {
            if (!empty($value)) {
                $check = TagsForAgent::firstOrCreate(['name' => $value]);
                array_push($tag_id, $check->id);
            }
        }

        //handal image upload
        if ($request->hasFile('profile_picture')) {
            $folder = str_pad(Auth::user()->id, 8, '0', STR_PAD_LEFT);
            $folder = 'client_' . $folder;
            $file = $request->file('profile_picture');
            $file_name = uniqid() . '.' .  $file->getClientOriginalExtension();
            $s3filePath = '/assets/' . $folder . '/agents' . $file_name;
            $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
            $getFileName = $path;
        }

        foreach ($request->only('name', 'type', 'vehicle_type_id', 'make_model', 'plate_number', 'phone_number', 'color', 'uid') as $key => $value) {
            $agent->{$key} = $value;
        }
        $agent->team_id         = $request->team_id;
        $agent->profile_picture = $getFileName;
        $agent->save();

        $agent->tags()->sync($tag_id);

        $driver_registration_documents = DriverRegistrationDocument::get();
        foreach ($driver_registration_documents as $driver_registration_document) {
            $name = str_replace(" ", "_", $driver_registration_document->name);
            if ($driver_registration_document->file_type != "Text") {
                if ($request->hasFile($name)) {
                    $folder = str_pad(Auth::user()->code, 8, '0', STR_PAD_LEFT);
                    $folder = 'client_' . $folder;
                    $file = $request->file($name);
                    $file_name = uniqid() . '.' . $file->getClientOriginalExtension();
                    $s3filePath = '/assets/' . $folder . '/agents' . $file_name;
                    $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
                    $getFileName = $path;
                    $agent_docs = AgentDocs::firstOrNew(['agent_id' => $agent->id, 'label_name' => $driver_registration_document->name, 'file_type' => $driver_registration_document->file_type]);
                    $agent_docs->file_name = $getFileName;
                    $agent_docs->save();
                }
            } else {
                $agent_docs = AgentDocs::firstOrNew(['agent_id' => $agent->id, 'label_name' => $driver_registration_document->name, 'file_type' => $driver_registration_document->file_type]);
                $agent_docs->file_name = $request->$name;
                $agent_docs->save();
            }
        }

        if ($agent) {
            return response()->json([
                'status' => 'success',
                'message' => 'Agent updated Successfully!',
                'data' => $agent
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        DriverGeo::where('driver_id', $id)->delete();  // i have to fix it latter
        Agent::where('id', $id)->delete();
        return redirect()->back()->with('success',__('Agent deleted successfully!'));
    }

    public function payreceive(Request $request, $domain = '')
    {
        try{
            $driver_id = $request->driver_id;
            $agent = Agent::where('id', $driver_id)->where('is_approved', 1)->first();
            $amount = $request->amount;
            $wallet = $agent->wallet;
            if ($amount > 0) {
                if($request->payment_type == 1){
                    $wallet->depositFloat($amount, ['Wallet has been <b>Credited</b>']);
                }
                elseif($request->payment_type == 2){
                    if($amount > $agent->balanceFloat){
                        return $this->error(__('Amount is greater than agent available funds'), 422);
                    }
                    $wallet->withdrawFloat($amount, ['Wallet has been <b>Dedited</b>']);
                }
                else{
                    return $this->error(__('Invalid Data'), 422);
                }
                return $this->success('', __('Payment is successfully completed'), 201);
            }else{
                return $this->error(__('Insufficient Amount'), 422);
            }

            // $data = [
            //     'driver_id' => $request->driver_id,
            //     'cr' => $request->payment_type == 1 ? $request->amount : null,
            //     'dr' => $request->payment_type == 2 ? $request->amount : null,
            // ];

            // $agent = AgentPayment::create($data);
        }
        catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function agentPayDetails($domain = '', $id)
    {
        $data = [];
        $agent = Agent::where('id', $id)->first();
        if (isset($agent)) {
            $cash  = $agent->order->sum('cash_to_be_collected');
            $driver_cost  = $agent->order->sum('driver_cost');
            $order = $agent->order->sum('order_cost');
            $credit = $agent->agentPayment->sum('cr');
            $debit = $agent->agentPayment->sum('dr');
        } else {
            $cash  = 0;
            $order = 0;
            $driver_cost = 0;
            $credit = 0;
            $debit = 0;
        }

        $data['cash_to_be_collected'] = $cash;
        $data['order_cost']           = $order;
        $data['driver_cost']           = $driver_cost;
        $data['credit']           = $credit;
        $data['debit']           = $debit;
        $data['wallet_balance']           = $agent->balanceFloat;


        return response()->json($data);
    }

    /* Change Agent approval status */

    public function approval_status(Request $request)
    {
        try {
            $agent_approval = Agent::find($request->id);
            $agent_approval->is_approved = $request->is_approved;
            $agent_approval->save();
            return response()->json(['status' => 1, 'message' => 'Status change successfully.']);
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

    /* Change Agent approval status */

    public function change_approval_status(Request $request)
    {
        try {
            $agent_approval = Agent::find($request->id);
            $agent_approval->is_approved = $request->status;
            $agent_approval->save();
            return response()->json(['status' => 1, 'message' => 'Status change successfully.']);
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

}
