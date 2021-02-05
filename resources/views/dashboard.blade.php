@extends('layouts.vertical', ['title' => 'Dashboard','demo'=>'creative'])

@section('css')
    <!-- Plugins css -->
    {{-- demo/css/style.css is only for dashboard css --}}
    <link href="{{ asset('demo/css/style.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
@endsection
@php
$color = ['one','two','three','four','five','six','seven','eight'];
@endphp

@section('content')

<!-- Bannar Section -->
{{-- <section class="bannar header-setting"> --}}
<div class="container-fluid p-0">
<div class="row coolcheck">
    <div class="col-md-4 col-xl-3 left-sidebar pt-3">
        <div id="accordion">
            <div class="card no-border-radius">
               

                    <div class="card-header" id="heading-1">

                            <a role="button" data-toggle="collapse" href="#collapse-new"
                                aria-expanded="true" aria-controls="collapse-new">
                                <div class="newcheckit">
                                    <div class="row d-flex align-items-center" class="mb-0">
                                        <div class="col-md-4 col-lg-3 col-xl-2 col-2">
                                            <span class="profile-circle">U</span>
                                        </div>
                                        <div class="col-md-8 col-lg-9 col-xl-10 col-10">
                                            <h6 class="header-title">Unassigned</h6>
                                            <p class="mb-0">{{isset($unassigned[0]['agent_count'])?$unassigned[0]['agent_count']:''}} Agents : <span>{{isset($unassigned[0]['offline_agents'])?$unassigned[0]['offline_agents']:''}} Offline ・ {{isset($unassigned[0]['online_agents'])?$unassigned[0]['online_agents']:''}} Online</span></p>
                                        </div>
                                    </div>
                                </div>

                            </a>

                    </div>

                    <div id="collapse-new" class="collapse" data-parent="#accordion"
                        aria-labelledby="heading-1">
                        <div class="card-body">
                              
                            @foreach ($unassigned as $agent)
                             
                                <div id="accordion-{{ $agent['id'] }}">
                                    <div class="card">
                                        <div class="card-header profile-status ml-2" id="by{{ $agent['id'] }}">

                                                <a class="profile-block collapsed" role="button"
                                                    data-toggle="collapse" href="#collapse{{ $agent['id'] }}"
                                                    aria-expanded="false"
                                                    aria-controls="collapse{{ $agent['id'] }}">
                                                    <div class="">
                                                        <div class="row d-flex align-items-center">
                                                            <div class="col-md-4 col-lg-3 col-xl-2">
                                                                <img class="profile-image"
                                                                    src="https://dummyimage.com/36x36/ccc/fff">
                                                            </div>
                                                            <div class="col-md-8 col-lg-9 col-xl-10">
                                                                <h6 class="mb-0 header-title scnd">{{ $agent['name'] }}</h6>
                                                                <p class="mb-0">{{$agent['free'].' '}} <span>{{$agent['agent_task_count']}} Tasks</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>

                                        </div>
                                        <div id="collapse{{ $agent['id'] }}" class="collapse"
                                            data-parent="#accordion-{{ $agent['id'] }}"
                                            aria-labelledby="by{{ $agent['id'] }}">
                                           
                                            @foreach($agent['order'] as $orders)
                                                @foreach($orders['task'] as $tasks)
                                                    <div class="card-body">
                                                        <div class="pt-3 pl-3 pr-3 assigned-block mb-1">
                                                            <div class="wd-10">
                                                                <img class="vt-top"
                                                                    src="{{ asset('demo/images/ic_location_blue_1.png') }}">
                                                            </div>
                                                            <div class="wd-90">
                                                                <h6>{{ $tasks['location']['address'] }}</h6>
                                                                <span>{{ $tasks['location']['short_name'] }}</span>
                                                                <h5 class="mb-1"><span>Due</span>
                                                                    {{date('h:i a ', strtotime($tasks->created_at))}}
                                                                </h5>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        {{-- <a class="view-task-details"
                                                                            href="">View Task
                                                                            Details</a> --}}
                                                                    </div>
                                                                    <div class="col-md-6 text-right">
                                                                        <button
                                                                            class="assigned-btn">Assigned</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                
            </div>
            <div class="card no-border-radius">
                @foreach ($teams as $item)
                    
                   
                    <div class="card-header" id="heading-1">
                       
                            <a role="button" data-toggle="collapse" href="#collapse-{{ $item['id'] }}"
                                aria-expanded="true" aria-controls="collapse-{{ $item['id'] }}">
                                <div class="newcheckit">
                                    <div class="row d-flex align-items-center" class="mb-0">
                                        <div class="col-md-3 col-xl-2 col-2">
                                            <span class="profile-circle {{$color[rand(0,7)]}}">{{ $item['name'][0] }}</span>
                                        </div>
                                        <div class="col-md-9 col-xl-10 col-10">
                                            <h6 class="header-title">{{ $item['name'] }}</h6>
                                            <p class="mb-0">{{count($item['agents'])}} Agents : <span>{{$item['online_agents']}} Online ・ {{$item['offline_agents']}} Offline</span></p>
                                        </div>
                                    </div>
                                </div>

                            </a>

                    </div>

                    <div id="collapse-{{ $item['id'] }}" class="collapse" data-parent="#accordion"
                        aria-labelledby="heading-1">
                        <div class="card-body">


                            @foreach ($item['agents'] as $agent)
                                <div id="accordion-{{ $agent['id'] }}">
                                    <div class="card no-border-radius">
                                        <div class="card-header ml-2" id="by{{ $agent['id'] }}">

                                                <a class="profile-block collapsed" role="button"
                                                    data-toggle="collapse" href="#collapse{{ $agent['id'] }}"
                                                    aria-expanded="false"
                                                    aria-controls="collapse{{ $agent['id'] }}">
                                                    <div class="row">
                                                        <div class="col-md-2 col-2">
                                                            <img class="profile-circle"
                                                                src="{{isset($agent['profile_picture']) ? Phumbor::url(Storage::disk('s3')->url($agent['profile_picture']))->fitIn(55,30):'https://dummyimage.com/36x36/ccc/fff'}}">
                                                        </div>
                                                        <div class="col-md-10 col-10">
                                                            <h6 class="mb-0 header-title scnd">{{ $agent['name'] }}</h6>
                                                            <p class="mb-0">{{count($agent['order'])>0?'Busy  ':'Free  '}}<span>{{$agent['agent_task_count']}} Tasks</span></p>
                                                        </div>
                                                    </div>
                                                </a>
                                        </div>
                                        <div id="collapse{{ $agent['id'] }}" class="collapse"
                                            data-parent="#accordion-{{ $agent['id'] }}"
                                            aria-labelledby="by{{ $agent['id'] }}">
                                            @foreach ($agent['order'] as $orders)
                                                @foreach ($orders['task'] as $tasks)
                                                    <div class="card-body">
                                                        <div class="pt-3 pl-3 pr-3 assigned-block">
                                                            <div class="wd-10">
                                                                <img class="vt-top"
                                                                    src="{{ asset('demo/images/ic_location_blue_1.png') }}">
                                                            </div>
                                                            <div class="wd-90">
                                                                <h6>{{ $tasks['location']['address'] }}</h6>
                                                                <span>{{ $tasks['location']['short_name'] }}</span>
                                                                <h5 class="mb-1"><span>Due</span>
                                                                    {{date('h:i a ', strtotime($tasks->created_at))}}
                                                                    <button
                                                                            class="assigned-btn float-right">Assigned</button>
                                                                </h5>

                                                            </div>
                                                            <div class="pb-3">

                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                @endforeach
                                                
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>


        </div>
    </div>
    <div class="col-md-8 col-xl-9">
        <div class="map-wrapper">
            <div style="width: 100%">
                <div id="map_canvas" style="width: 100%; height:95vh;"></div>
            </div>
            <div class="contant">
                <div class="bottom-content">
                <input type="text" placeholder="mm/dd/yy" id="basic-datepicker" class="brdr-1 datetime" value="{{date('Y-m-d')}}">
                    <div class="dropdown d-inline-block brdr-1">
                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="mr-1"
                                src="{{ asset('demo/images/ic_assigned_to.png') }}">{{ count($teams)+1 }}
                            Teams
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <div class="task-block pl-2 pr-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span>Tasks</span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href=""><span>All</span></a>
                                        <a class="ml-3" href=""><span>None</span></a>
                                    </div>
                                </div>

                                <div class="row mt-2 teamchange">
                                    <div class="col-md-8">
                                        <h6>All Teams</h6>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <label class="">
                                            <input class="newchecks" cla type="checkbox" value="-1"
                                                name="teamchecks[]" checked>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2 teamchange">
                                    <div class="col-md-8">
                                        <h6>Unassigned team</h6>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <label class="">
                                            <input class="newchecks" cla type="checkbox" value="0"
                                                name="teamchecks[]">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                @foreach ($teams as $item)
                                    <div class="row mt-2 teamchange">
                                        <div class="col-md-8">
                                            <h6>{{ $item['name'] }}</h6>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <label class="">
                                                <input class="newchecks" type="checkbox" name="teamchecks[]"
                                                    value="{{ $item['id'] }}">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="dropdown d-inline-block brdr-1">
                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="mr-1" src="{{ asset('demo/images/ic_time.png') }}">Tasks
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <div class="task-block pl-2 pr-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span>Task Status </span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href=""><span></span></a>
                                        <a class="ml-3" href=""><span></span></a>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-9">
                                        <h6><img class="mr-2"
                                                src=""></span>All
                                        </h6>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <label class="mt-2">
                                            <input class="taskchecks" type="checkbox" name="taskstatus[]"
                                                value="5">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-9">
                                        <h6><img class="mr-2"
                                                src="{{ asset('assets/newicons/red.png') }}"></span>Unassigned
                                        </h6>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <label class="mt-2">
                                            <input class="taskchecks" type="checkbox" name="taskstatus[]"
                                                value="0">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-9">
                                        <h6><img class="mr-2"
                                                src="{{ asset('assets/newicons/orange.png') }}"></span>Assigned
                                        </h6>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <label class="mt-2">
                                            <input class="taskchecks" type="checkbox" name="taskstatus[]"
                                                value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-9">
                                        <h6><img class="mr-2"
                                                src="{{ asset('assets/newicons/green.png') }}"></span>Completed
                                        </h6>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <label class="mt-2">
                                            <input class="taskchecks" type="checkbox" name="taskstatus[]"
                                                value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-9">
                                        <h6><img class="mr-2"
                                                src="{{ asset('assets/newicons/grey.png') }}"></span>Failed
                                        </h6>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <label class="mt-2">
                                            <input class="taskchecks" type="checkbox" name="taskstatus[]"
                                                value="4">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown d-inline-block">
                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="mr-1" src="{{ asset('demo/images/ic_time.png') }}">Drivers
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <div class="task-block pl-2 pr-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span>Drivers</span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href=""><span></span></a>
                                        <a class="ml-3" href=""><span></span></a>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-8">
                                        <h6>All Drivers</h6>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <label class="">
                                            <input class="agentdisplay" type="checkbox" name="agentcheck[]" value="2">
                                            <span class="checkmark" ></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-8">
                                        <h6><span class="circle lia-castro mr-2"></span>Online</h6>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <label class="">
                                            <input class="agentdisplay" type="checkbox" name="agentcheck[]" value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-8">
                                        <h6><span class="circle mr-2"></span>Offline</h6>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <label class="">
                                            <input class="agentdisplay" type="checkbox" name="agentcheck[]" value="0">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


@endsection


@section('script')
@include('dashboardscript')
@endsection
