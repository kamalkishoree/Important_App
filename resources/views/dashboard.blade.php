@extends('layouts.vertical', ['title' => 'Dashboard','demo'=>'creative'])

@section('css')
<!-- Plugins css -->
<link href="{{ asset('demo/css/style.css') }}" rel="stylesheet" type="text/css" />
@endsection
@php
$color = ['one','two','three','four','five','six','seven','eight'];
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp
@section('content')

<!-- Bannar Section -->
{{-- <section class="bannar header-setting"> --}}
   
<div class="container-fluid p-0">
<div class="row coolcheck">
    <div class="pageloader" style="display: none;">
        <div class="box">
            <h4 class="routetext"></h4>
            <div class="spinner-border avatar-lg text-primary m-2" role="status"></div>
        </div>
    </div>   
    

    <div class="col-md-4 col-xl-3 left-sidebar pt-3">  
        <div class="side_head d-flex justify-content-between align-items-center mb-2">
            <i class="mdi mdi-sync mr-1" onclick="reloadData()" aria-hidden="true"></i> 
            <span class="allAccordian"><span class="" onclick="openAllAccordian()">Open All</span></span>
        </div>
        <div id="accordion" class="overflow-hidden">
            <div class="card no-border-radius">
                
                <?php
                    //for ($u=0; $u < count($routedata) ; $u++) { ?>
                        {{-- <span id="directions-panel<?php //echo $u?>"></span>
                        <span id="waypoints<?=//$u?>"></span> --}}
                    <?php //} ?>
                    <div class="card-header" id="heading-1">

                            <a role="button" data-toggle="collapse" href="#collapse-new"
                                aria-expanded="false" aria-controls="collapse-new">
                                <div class="newcheckit">
                                    <div class="row d-flex align-items-center" class="mb-0">
                                        <div class="col-md-4 col-lg-3 col-xl-2 col-2">
                                            <span class="profile-circle">U</span>
                                        </div>
                                        <div class="col-md-8 col-lg-9 col-xl-10 col-10">
                                            <h6 class="header-title">Unassigned</h6>
                                            {{-- <p class="mb-0">{{isset($unassigned[0]['agent_count'])?$unassigned[0]['agent_count']:''}} Agents : <span>{{isset($unassigned[0]['offline_agents'])?$unassigned[0]['offline_agents']:''}} Offline ・ {{isset($unassigned[0]['online_agents'])?$unassigned[0]['online_agents']:''}} Online</span></p> --}}
                                        </div>
                                    </div>
                                </div>

                            </a>

                    </div>

                    <div id="collapse-new" class="collapse" data-parent="#accordion"
                        aria-labelledby="heading-1">
                        <div class="card-body">


                            <div id="accordion-0">
                                <div class="card no-border-radius">
                                    <div class="card-header ml-2" id="by0">

                                        <?php

                                        if(isset($distance_matrix[0]))
                                        {
                                            if($unassigned_orders[0]['task_order']==0){
                                                $opti0 = "yes";
                                            }else{
                                                $opti0 = "";
                                            }                                       
                                            
                                            $routeperams0 = "'".$distance_matrix[0]['tasks']."','".json_encode($distance_matrix[0]['distance'])."','".$opti0."',0";
                                            
                                            $optimize0 = '<span class="optimize_btn" onclick="RouteOptimization('.$routeperams0.')">Optimize</span>';
                                            $params0 = "'".$distance_matrix[0]['tasks']."','".json_encode($distance_matrix[0]['distance'])."','yes',0";
                                        }else{
                                            $optimize0="";
                                            $params0 = "";
                                        }
                                        ?>

                                        <a class="profile-block collapsed" role="button" data-toggle="collapse" href="#collapse0" aria-expanded="false" aria-controls="collapse0">
                                            <div class="row">
                                                <div class="col-md-2 col-2">
                                                    <img class="profile-circle" src="https://dummyimage.com/36x36/ccc/fff">
                                                </div>
                                                <div class="col-md-10 col-10">                        
                                                    <h6 class="mb-0 header-title scnd">No Driver <div class="optimizebtn0">{!! $optimize0 !!} </div></h6>
                                                    <p class="mb-0"> <span>{{ count($unassigned_orders) }} Tasks</span></p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    
                                        
                                    <div id="collapse0" class="collapse" data-parent="#accordion-0" aria-labelledby="by0">
                                        <div id="handle-dragula-left0" class="dragable_tasks" agentid="0"  params="{{ $params0 }}">            
                                    
                                            @foreach($unassigned_orders as $orders)
                                                @foreach($orders['task'] as $tasks)
                                                    <div class="card-body" task_id ="{{ $tasks['id'] }}">
                                                        <div class="p-2 assigned-block">
                                                        @php
                                                                $st = "Unassigned";
                                                                $color_class = "assign_";
                                                               
                            
                                                                if($tasks['task_type_id']==1)
                                                                {
                                                                    $tasktype = "Pickup";
                                                                    $pickup_class = "yellow_";
                                                                }elseif($tasks['task_type_id']==2)
                                                                {
                                                                    $tasktype = "Dropoff";
                                                                    $pickup_class = "green_";
                                                                }else{
                                                                    $tasktype = "Appointment";
                                                                    $pickup_class = "assign_";
                                                                }
                                                        @endphp
                                                        
                                                            <div>
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col-9 d-flex">
                                                                        <h5 class="d-inline-flex align-items-center justify-content-between"><i class="fas fa-bars"></i> <span>{{date('h:i a ', strtotime($tasks['created_at']))}}</span></h5>
                                                                        <h6 class="d-inline"><img class="vt-top"
                                                                            src="{{ asset('demo/images/ic_location_blue_1.png') }}"> {{ isset($tasks['location']['address'])? $tasks['location']['address']:'' }} <span class="d-block">{{ isset($tasks['location']['short_name'])? $tasks['location']['short_name']:'' }}</span></h6>
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <button class="assigned-btn float-right mb-2 {{$pickup_class}}">{{$tasktype}}</button>
                                                                        <button class="assigned-btn float-right {{$color_class}}">{{$st}}</button>
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
                            </div>



                        </div>
                    </div>
                
            </div>
            <div class="card no-border-radius">
                
                
                @foreach ($teams as $item)
                    
                   
                    <div class="card-header" id="heading-1">
                       
                            <a role="button" data-toggle="collapse" href="#collapse-{{ $item['id'] }}"
                                aria-expanded="false" aria-controls="collapse-{{ $item['id'] }}">
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

                            <?php //echo "<pre>";
                               // print_r($item['agents']); die;?>
                            @foreach ($item['agents'] as $agent)

                                <?php
                                
                                    //print_r($distance_matrix[$agent['id']]); die;

                                if(isset($distance_matrix[$agent['id']]))
                                {
                                    if($agent['order'][0]['task_order']==0){
                                        $opti = "yes";
                                    }else{
                                        $opti = "";
                                    }
                                
                                    //print_r($distance_matrix[$agent['id']]); 
                                    $routeperams = "'".$distance_matrix[$agent['id']]['tasks']."','".json_encode($distance_matrix[$agent['id']]['distance'])."','".$opti."',".$agent['id'];
                                    
                                    $optimize = '<span class="optimize_btn" onclick="RouteOptimization('.$routeperams.')">Optimize</span>';
                                    $params = "'".$distance_matrix[$agent['id']]['tasks']."','".json_encode($distance_matrix[$agent['id']]['distance'])."','yes',".$agent['id'];
                                }else{
                                    $optimize="";
                                    $params = "";
                                }
                                
                                ?>
                            
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
                                                                src="{{isset($agent['profile_picture']) ? $imgproxyurl.Storage::disk('s3')->url($agent['profile_picture']):'https://dummyimage.com/36x36/ccc/fff'}}">
                                                        </div>
                                                        <div class="col-md-10 col-10">
                                                            <h6 class="mb-0 header-title scnd">{{ $agent['name'] }} <div class="optimizebtn{{ $agent['id'] }}">{!! $optimize !!} </div></h6>
                                                            <p class="mb-0">{{count($agent['order'])>0?'Busy  ':'Free  '}}<span>{{$agent['agent_task_count']}} Tasks</span></p>
                                                        </div>
                                                    </div>
                                                </a>
                                        </div>
                                        <div id="collapse{{ $agent['id'] }}" class="collapse"
                                            data-parent="#accordion-{{ $agent['id'] }}"
                                            aria-labelledby="by{{ $agent['id'] }}">
                                            <div id="handle-dragula-left{{ $agent['id'] }}" class="dragable_tasks" agentid="{{ $agent['id'] }}"  params="{{ $params }}">
                                                
                                            @foreach ($agent['order'] as $orders)
                                                
                                                @foreach ($orders['task'] as $tasks)
                                                
                                                    <div class="card-body" task_id ="{{ $tasks['id'] }}">
                                                        <div class="p-2 assigned-block">
                                                            
                                                            @php
                                                                    if($tasks['task_status']==1)
                                                                    {
                                                                    $st = "Assigned";
                                                                    $color_class = "assign_";                                                                    
                                                                    }elseif($tasks['task_status']==2)
                                                                    {
                                                                    $st = "Started";
                                                                    $color_class = "yellow_";
                                                                    }elseif($tasks['task_status']==3)
                                                                    {
                                                                    $st = "Arrived";
                                                                    $color_class = "light_green";
                                                                    }elseif($tasks['task_status']==4)
                                                                    {
                                                                    $st = "Completed";
                                                                    $color_class = "green_";
                                                                    }else{
                                                                    $st = "Failed";
                                                                    $color_class = "red_";
                                                                    }

                                                                    if($tasks['task_type_id']==1)
                                                                    {
                                                                        $tasktype = "Pickup";
                                                                        $pickup_class = "yellow_";
                                                                    }elseif($tasks['task_type_id']==2)
                                                                    {
                                                                        $tasktype = "Dropoff";
                                                                        $pickup_class = "green_";
                                                                    }else{
                                                                        $tasktype = "Appointment";
                                                                        $pickup_class = "assign_";
                                                                    }
                                                            @endphp
                                                            <div>

                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col-9 d-flex">
                                                                        <h5 class="d-inline-flex align-items-center justify-content-between"><i class="fas fa-bars"></i> <span>{{date('h:i a ', strtotime($tasks['created_at']))}}</span></h5>
                                                                        <h6 class="d-inline"><img class="vt-top"
                                                                            src="{{ asset('demo/images/ic_location_blue_1.png') }}"> {{ isset($tasks['location']['address'])? $tasks['location']['address']:'' }} <span class="d-block">{{ isset($tasks['location']['short_name'])? $tasks['location']['short_name']:'' }}</span></h6>
                                                                        
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <button class="assigned-btn float-right mb-2 {{$pickup_class}}">{{$tasktype}}</button>
                                                                        <button class="assigned-btn float-right {{$color_class}}">{{$st}}</button>
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
                    <input type="text"  id="basic-datepicker" class="brdr-1 datetime" value="{{date($preference->date_format, strtotime($date))}}" data-date-format="{{$preference->date_format}}">
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
                                    <div class="col-md-6 col-6">
                                        <span>Tasks</span>
                                    </div>
                                    <div class="col-md-6 col-6 text-right">
                                        <a href=""><span>All</span></a>
                                        <a class="ml-3" href=""><span>None</span></a>
                                    </div>
                                </div>

                                <div class="row mt-2 teamchange">
                                    <div class="col-md-8 col-9">
                                        <h6>All Teams</h6>
                                    </div>
                                    <div class="col-md-4 col-3 text-right">
                                        <label class="">
                                            <input class="newchecks filtercheck teamchecks" cla type="checkbox" value="-1"
                                                name="teamchecks[]" checked>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2 teamchange">
                                    <div class="col-md-8 col-9">
                                        <h6>Unassigned team</h6>
                                    </div>
                                    <div class="col-md-4 col-3 text-right">
                                        <label class="">
                                            <input class="newchecks filtercheck teamchecks" cla type="checkbox" value="0"
                                                name="teamchecks[]">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                @foreach ($teams as $item)
                                    <div class="row mt-2 teamchange">
                                        <div class="col-md-8 col-9">
                                            <h6>{{ $item['name'] }}</h6>
                                        </div>
                                        <div class="col-md-4 col-3 text-right">
                                            <label class="">
                                                <input class="newchecks filtercheck teamchecks" type="checkbox" name="teamchecks[]"
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
                                    <div class="col-md-6 col-6">
                                        <span>Task Status </span>
                                    </div>
                                    <div class="col-md-6 col-6 text-right">
                                        <a href=""><span></span></a>
                                        <a class="ml-3" href=""><span></span></a>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-9 col-9">
                                        <h6><img class="mr-2"
                                                src=""></span>All
                                        </h6>
                                    </div>
                                    <div class="col-md-3 col-3 text-right">
                                        <label class="mt-2">
                                            <input class="taskchecks filtercheck" type="checkbox" name="taskstatus[]"
                                                value="5">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-9 col-9">
                                        <h6><img class="mr-2"
                                                src="{{ asset('assets/newicons/red.png') }}"></span>Unassigned
                                        </h6>
                                    </div>
                                    <div class="col-md-3 col-3 text-right">
                                        <label class="mt-2">
                                            <input class="taskchecks filtercheck" type="checkbox" name="taskstatus[]"
                                                value="0">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-9 col-9">
                                        <h6><img class="mr-2"
                                                src="{{ asset('assets/newicons/orange.png') }}"></span>Assigned
                                        </h6>
                                    </div>
                                    <div class="col-md-3 col-3 text-right">
                                        <label class="mt-2">
                                            <input class="taskchecks filtercheck" type="checkbox" name="taskstatus[]"
                                                value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-9 col-9">
                                        <h6><img class="mr-2"
                                                src="{{ asset('assets/newicons/green.png') }}"></span>Completed
                                        </h6>
                                    </div>
                                    <div class="col-md-3 col-3 text-right">
                                        <label class="mt-2">
                                            <input class="taskchecks filtercheck" type="checkbox" name="taskstatus[]"
                                                value="3">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-9 col-9">
                                        <h6><img class="mr-2"
                                                src="{{ asset('assets/newicons/grey.png') }}"></span>Failed
                                        </h6>
                                    </div>
                                    <div class="col-md-3 col-3 text-right">
                                        <label class="mt-2">
                                            <input class="taskchecks filtercheck" type="checkbox" name="taskstatus[]"
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
                                            <input class="agentdisplay filtercheck agentcheck" type="checkbox" name="agentcheck[]" value="2">
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
                                            <input class="agentdisplay filtercheck agentcheck" type="checkbox" name="agentcheck[]" value="1">
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
                                            <input class="agentdisplay filtercheck agentcheck" type="checkbox" name="agentcheck[]" value="0">
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

<?php   // for setting default location on map
    $agentslocations = array();
    foreach ($agents as $singleagent) {
        if((!empty($singleagent['agentlog'])) && ($singleagent['agentlog']['lat']!=0) && ($singleagent['agentlog']['long']!=0))
        {
            // lat: allagent[0].agentlog && allagent[0].agentlog['lat']  != "0.00000000" ? parseFloat(allagent[0].agentlog['lat']): 30.7046,
            $agentslocations[] = $singleagent['agentlog'];
        }        
    }
    $defaultmaplocation['lat'] = 30.7046;
    $defaultmaplocation['long'] = 76.7179;
    $agentslocations[] = $defaultmaplocation;
    
    //print_r($agentslocations);

?>



@section('script')


{{-- <script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/clockpicker/clockpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

<!-- Page js-->
<script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script> --}}
{{-- <script src="{{ asset('demo/js/propeller.min.js') }}"></script>
--}}
{{-- <script src="{{asset('assets/libs/dragula/dragula.min.js')}}"></script>
<script src="{{asset('assets/js/pages/dragula.init.js')}}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.6.3/jquery.timeago.js"></script>
<script>

$(document).ready(function() {

initMap();
$('#shortclick').trigger('click');
$(".timeago").timeago();
});

function gm_authFailure() {

    $('.excetion_keys').append('<span><i class="mdi mdi-block-helper mr-2"></i> <strong>Google Map</strong> key is not valid</span><br/>');
    $('.displaySettingsError').show();
};

// var marker;
var show = [0];
let map;
let markers = [];

var url = window.location.origin;
// $("#abc").click(function() {
//     $(this).data('id');
// });
var olddata  = {!!json_encode($newmarker)!!};
var allagent = {!!json_encode($agents)!!};

// for getting default map location
var defaultmaplocation = {!!json_encode($agentslocations)!!};
var defaultlat = parseFloat(defaultmaplocation[0].lat);
var defaultlong = parseFloat(defaultmaplocation[0].long);



var imgproxyurl         = {!!json_encode($imgproxyurl)!!};


// var teamdata = {!!json_encode($teams)!!};
// var cars    = [0];

//$('.newchecks').click(function() {
$('.filtercheck').click(function() {    
// for teams
var val = [];
$('.newchecks:checkbox:checked').each(function(i) {
    val[i] = parseInt($(this).val());
});

//for tasks
var taskval = [];
$('.taskchecks:checkbox:checked').each(function(i) {
    taskval[i] = parseInt($(this).val());    
});

//for drivers
var agentval = [];
$('.agentdisplay:checkbox:checked').each(function(i) {
    agentval[i] = parseInt($(this).val());
});


setMapOnAll(null);
//$(".taskchecks").prop('checked', false);
//$(".agentdisplay").prop('checked', false);
//   if (!$(this).is(':checked')) {
//    return confirm("Are you sure?");
//   }
//console.log(val);

//main task markers
for (let i = 0; i < olddata.length; i++) {
    checkdata = olddata[i];
    var info = []
    //alert(val);
    // addMarker({ lat: checkdata[3], lng: checkdata[4] });
    if ($.inArray(checkdata['team_id'], val) != -1 || $.inArray(-1, val) != -1) {
        
        var urlnewcreate = '';
            if(checkdata['task_status'] == 0){
                urlnewcreate = 'unassigned';
            }else if(checkdata['task_status'] == 1 || checkdata['task_status'] == 2){
                urlnewcreate = 'assigned';
            }else if(checkdata['task_status'] == 3){
                urlnewcreate = 'complete';
            }else{
                urlnewcreate = 'faild';
            }
            
            if(checkdata['task_type_id'] == 1){
                    urlnewcreate += '_P.png';
            }else if(checkdata['task_type_id'] == 2){
                    urlnewcreate +='_D.png';
            }else{
                    urlnewcreate +='_A.png';
            }    
        
        image = '{{ asset('assets/newicons/') }}'+'/'+urlnewcreate;

        send = null;
        type = 1;

        addMarker({
            lat:  parseFloat(checkdata['latitude']),
            lng:  parseFloat(checkdata['longitude'])
        }, send, image,checkdata,type);
    }

    // for tasks

        if($.inArray(checkdata['task_status'], taskval) !== -1 || $.inArray(5, taskval) != -1) {
            
            var urlnewcreate = '';
            if(checkdata['task_status'] == 0){
                urlnewcreate = 'unassigned';
            }else if(checkdata['task_status'] == 1 || checkdata['task_status'] == 2){
                urlnewcreate = 'assigned';
            }else if(checkdata['task_status'] == 3){
                urlnewcreate = 'complete';
            }else{
                urlnewcreate = 'faild';
            }
            
                if(checkdata['task_type_id'] == 1){
                    urlnewcreate += '_P.png';
                }else if(checkdata['task_type_id'] == 2){
                    urlnewcreate +='_D.png';
                }else{
                    urlnewcreate +='_A.png';
                }
                
                image = '{{ asset('assets/newicons/') }}'+'/'+urlnewcreate;
                
                send = null;
                type = 1;
            addMarker({lat:parseFloat(checkdata['latitude']),lng:parseFloat(checkdata['longitude'])}, send,image,checkdata,type);
        }    
}

    //for agents
    for (let i = 0; i < allagent.length; i++) {
        checkdata = allagent[i];
        //for agents
        if ($.inArray(checkdata['is_available'], agentval) != -1 || $.inArray(2, agentval) != -1) {
            
            if (checkdata['is_available'] == 1) {
                images = url+'/demo/images/location.png';
            }else {
                images = url+'/demo/images/location_grey.png';
            }
            var image = {
             url: images, // url
             scaledSize: new google.maps.Size(50, 50), // scaled size
             origin: new google.maps.Point(0,0), // origin
             anchor: new google.maps.Point(22,22) // anchor
            };
            send = null;
            type = 2;
           addMarker({lat: parseFloat(checkdata.agentlog['lat']),lng:  parseFloat(checkdata.agentlog['long'])}, send, image,checkdata,type);
        }
    }

});

//$('.taskchecks').click(function() {
$('.taskchecks_old').click(function() {
var taskval = [];
$('.taskchecks:checkbox:checked').each(function(i) {
    taskval[i] = parseInt($(this).val());

});

setMapOnAll(null);
$(".newchecks").prop('checked', false);
$(".agentdisplay").prop('checked', false); 
//$('.taskchecks:checkbox').removeAttr('checked');
//   if (!$(this).is(':checked')) {
//    return confirm("Are you sure?");
//   }
for (let i = 0; i < olddata.length; i++) {
    checkdata = olddata[i];
   // console.log(checkdata);
    //console.log(checkdata[5]);
    // addMarker({ lat: checkdata[3], lng: checkdata[4] });
    //alert(checkdata['task_status']);
    if($.inArray(checkdata['task_status'], taskval) !== -1 || $.inArray(5, taskval) != -1) {
        
        var urlnewcreate = '';
        if(checkdata['task_status'] == 0){
            urlnewcreate = 'unassigned';
        }else if(checkdata['task_status'] == 1 || checkdata['task_status'] == 2){
            urlnewcreate = 'assigned';
        }else if(checkdata['task_status'] == 3){
            urlnewcreate = 'complete';
        }else{
            urlnewcreate = 'faild';
        }
        
            if(checkdata['task_type_id'] == 1){
                urlnewcreate += '_P.png';
            }else if(checkdata['task_type_id'] == 2){
                urlnewcreate +='_D.png';
            }else{
                urlnewcreate +='_A.png';
            }
            
            image = '{{ asset('assets/newicons/') }}'+'/'+urlnewcreate;
            
            send = null;
            type = 1;
        addMarker({lat:parseFloat(checkdata['latitude']),lng:parseFloat(checkdata['longitude'])}, send,image,checkdata,type);
    }
}

});


// $('.agentdisplay').click(function() {
$('.agentdisplay_old').click(function() {
    var agentval = [];
    $('.agentdisplay:checkbox:checked').each(function(i) {
        agentval[i] = parseInt($(this).val());
    });
    setMapOnAll(null);
    $(".taskchecks").prop('checked', false);
    $(".newchecks").prop('checked', false);
    //   if (!$(this).is(':checked')) {
    //    return confirm("Are you sure?");
    //   }
    //console.log(agentval);


    for (let i = 0; i < allagent.length; i++) {
        checkdata = allagent[i];        
        // addMarker({ lat: checkdata[3], lng: checkdata[4] });
        if ($.inArray(checkdata['is_available'], agentval) != -1 || $.inArray(2, agentval) != -1) {
            
            if (checkdata['is_available'] == 1) {
                images = url+'/demo/images/location.png';
            }else {
                images = url+'/demo/images/location_grey.png';
            }
            var image = {
            url: images, // url
            scaledSize: new google.maps.Size(50, 50), // scaled size
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(22,22) // anchor
            };
            send = null;
            type = 2;
        addMarker({lat: parseFloat(checkdata.agentlog['lat']),lng:  parseFloat(checkdata.agentlog['long'])}, send, image,checkdata,type);
        }
    }

});




function initMap() {

    //console.log(allagent);

    const haightAshbury = {
        // lat: allagent[0].agentlog && allagent[0].agentlog['lat']  != "0.00000000" ? parseFloat(allagent[0].agentlog['lat']): 30.7046,
        // lng: allagent[0].agentlog && allagent[0].agentlog['long'] != "0.00000000" ? parseFloat(allagent[0].agentlog['long']):76.7179

        lat: allagent[0].agentlog && allagent[0].agentlog['lat']  != "0.00000000" ? parseFloat(allagent[0].agentlog['lat']): defaultlat,
        lng: allagent[0].agentlog && allagent[0].agentlog['long'] != "0.00000000" ? parseFloat(allagent[0].agentlog['long']):defaultlong
        
    };

    map = new google.maps.Map(document.getElementById("map_canvas"), {
        zoom: 12,
        center: haightAshbury,
        mapTypeId: "roadmap",
        styles: themeType,
    });

    
    //new code for route
    var color = [
        "blue",
        "green",
        "red",
        "purple",
        "skyblue",
        "yellow",
        "orange",
        
        ];

    var allroutes = {!! json_encode($routedata) !!};
    $.each(allroutes, function(i, item) {
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({suppressMarkers: true});
        if(i < color.length)
        {
            var routecolor = color[i];
        }else{
            var routecolor = "pink";
        }
        
        directionsRenderer.setOptions({
            polylineOptions: {
                strokeColor: routecolor                
            }
        });
        directionsRenderer.setMap(map);
        var al_task = allroutes[i].task_details;
        var agent_locatn = allroutes[i].driver_detail;
        calculateAndDisplayRoute(directionsService, directionsRenderer,map,al_task,agent_locatn);
    });

    // Adds a marker at the center of the map.
    for (let i = 0; i < olddata.length; i++) {
        checkdata = olddata[i];
    
        var urlnewcreate = '';
        if(checkdata['task_status'] == 0){
            urlnewcreate = 'unassigned';
        }else if(checkdata['task_status'] == 1 || checkdata['task_status'] == 2){
            urlnewcreate = 'assigned';
        }else if(checkdata['task_status'] == 3){
            urlnewcreate = 'complete';
        }else{
            urlnewcreate = 'faild';
        }
        
        if(checkdata['task_type_id'] == 1){
                urlnewcreate += '_P.png';
        }else if(checkdata['task_type_id'] == 2){
                urlnewcreate +='_D.png';
        }else{
                urlnewcreate +='_A.png';
        }
            
        img = '{{ asset('assets/newicons/') }}'+'/'+urlnewcreate;
           
        send = null;
            type = 1;
        addMarker({
            lat: parseFloat(checkdata['latitude']),
            lng:  parseFloat(checkdata['longitude'])
        }, send, img,checkdata,type);
    }

    
    //agents markers
    for (let i = 0; i < allagent.length; i++) {
            displayagent = allagent[i];
            
            if(displayagent.agentlog != null && displayagent.agentlog['lat'] != "0.00000000" && displayagent.agentlog['long'] != "0.00000000" ){
                console.log(displayagent.agentlog);
                        if (displayagent['is_available'] == 1) {
                            images = url+'/demo/images/location.png';
                        }else {
                            images = url+'/demo/images/location_grey.png';
                        }
                        var image = {
                        url: images, // url
                        scaledSize: new google.maps.Size(50, 50), // scaled size
                        origin: new google.maps.Point(0,0), // origin
                        anchor: new google.maps.Point(22,22) // anchor
                        };
                        send = null;
                        type = 2;

                        addMarker({lat: parseFloat(displayagent.agentlog['lat']),
                        lng:  parseFloat(displayagent.agentlog['long'])
                        }, send, image,displayagent,type);
            }
                       
    }
}



function calculateAndDisplayRoute(directionsService, directionsRenderer,map,alltask,agent_location) {    
            const waypts = [];
            const checkboxArray = document.getElementById("waypoints");

            for (let i = 0; i < alltask.length; i++) {
                if (i != alltask.length - 1 && alltask[i].task_status != 4 && alltask[i].task_status != 5 ) {
                   console.log(alltask[i]);
                    waypts.push({
                        location: new google.maps.LatLng(parseFloat(alltask[i].latitude), parseFloat(alltask[i]
                            .longitude)),
                        stopover: true,
                    });

                }
                var image = url+'/assets/newicons/'+alltask[i].task_type_id+'.png';

                // makeMarker({lat: parseFloat(alltask[i].latitude),lng:  parseFloat(alltask[i]
                //             .longitude)},image,map);
            }

            directionsService.route({
                    origin: new google.maps.LatLng(parseFloat(agent_location.lat), parseFloat(agent_location.long)),
                    destination: new google.maps.LatLng(parseFloat(alltask[alltask.length - 1].latitude),
                        parseFloat(alltask[alltask.length - 1].longitude)),
                    waypoints: waypts,
                    optimizeWaypoints: false,
                    travelMode: google.maps.TravelMode.DRIVING,
                },
                (response, status) => {
                    if (status === "OK" && response) {
                        directionsRenderer.setDirections(response);
                        // const route = response.routes[0];
                        // const summaryPanel = document.getElementById("directions-panel"+m);
                        // summaryPanel.innerHTML = "";

                        // // For each route, display summary information.
                        // for (let i = 0; i < route.legs.length; i++) {
                        //     const routeSegment = i + 1;
                        //     summaryPanel.innerHTML +=
                        //         "<b>Route Segment: " + routeSegment + "</b><br>";
                        //     summaryPanel.innerHTML += route.legs[i].start_address + " to ";
                        //     summaryPanel.innerHTML += route.legs[i].end_address + "<br>";
                        //     summaryPanel.innerHTML += route.legs[i].distance.text + "<br><br>";
                        // }
                    } else {
                        //window.alert("Directions request failed due to " + status);
                    }
                }
            );
        }


// Adds a marker to the map and push to the array.
function addMarker(location, lables, images,data,type) {

    var contentString = '';

    if(type == 1){

        contentString =
        '<div id="content">' +
        '<div id="siteNotice">' +
        "</div>" +
        '<h5 id="firstHeading" class="firstHeading">'+data['driver_name']+'</h5>' +
        '<h6 id="firstHeading" class="firstHeading">'+data['task_type']+'</h6>' +
        '<div id="bodyContent">' +
        "<p><b>Address :- </b> " +data['address']+ " " +
        ".</p>" +
        '<p><b>Customer: '+data['customer_name']+'</b>('+data['customer_phone_number']+') </p>' +
        "</div>" +
        "</div>";

    }else{

        img = data['image_url'];
        //console.log(img);
        contentString =

        '<div class="row no-gutters align-items-center">'+
            '<div class="col-sm-4">'+
                '<div class="img_box mb-sm-0 mb-2"> <img src="https://imgproxy.royodispatch.com/insecure/fit/200/200/sm/0/plain/'+data["image_url"]+'"/></div> </div>'+
            '<div class="col-sm-8 pl-2 user_info">'+
                '<div class="user_name mb-2"><label class="d-block m-0">'+data["name"]+'</label><span> <i class="fas fa-phone-alt"></i>'+data["phone_number"]+'</span></div>'+
                '<div><b class="d-block mb-2"><i class="far fa-clock"></i> <span> '+jQuery.timeago(new Date(data['agentlog']['created_at']))+
                ' </span></b> <b><i class="fas fa-mobile-alt"></i> '+data['agentlog']['device_type']+'</b> <b class="ml-2"> <i class="fas fa-battery-half"></i>  '+data['agentlog']['battery_level']+'%</b> </div>'
            '</div>'+
        '</div>';  
    }

    const infowindow = new google.maps.InfoWindow({
        content: contentString,
        minWidth: 250,
        minheight: 250,
    });
    
    const marker = new google.maps.Marker({
                    position: location,
                    label: lables,
                    icon: images,
                    map: map,
                    animation: google.maps.Animation.DROP,
                }); 
    
     markers.push(marker);

    marker.addListener("click", () => {
    infowindow.open(map, marker);
    });
}

// Sets the map on all markers in the array.
function setMapOnAll(map) {

    for (let i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
    setMapOnAll(null);
}

// Shows any markers currently in the array.
function showMarkers() { 
    setMapOnAll(map);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
    clearMarkers();
    markers = [];
}


$(".datetime").on('change', function postinput(){

    var matchvalue = $(this).val(); // this.value
    newabc =  url+'?date='+matchvalue;


    window.location.href = newabc;

});

// function makeMarker( position,icon,map) {
//             new google.maps.Marker({
//             position: position,
//             map: map,
//             icon: icon,
//             });
//          }


function RouteOptimization(taskids,distancematrix,optimize,agentid) {
    $('.routetext').text('Optimizing Route');    
    $('.pageloader').css('display','block');
    if(optimize=="yes")
    {
        $.ajax({
            type: 'POST',
            
            url: '{{url("/optimize-route")}}',
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            data: {'taskids':taskids,'distance':distancematrix,'agentid':agentid},

            success: function(response) {
                if(response!="Try again later")
                {
                    var data = $.parseJSON(response);
                // alert(data);
                    var tasklist = data.tasklist;
                    var taskorders = tasklist.order;
                    
                    //$('#collapse'+agentid).html('');
                    $('#handle-dragula-left'+agentid).html('');
                    //alert( taskorders.length);
                    for (var i = 0; i < taskorders.length; i++) {
                        var object = taskorders[i];
                        var task_id =  object['task'][0]['id'];
                        var location_address =  object['task'][0]['location']['address'];
                        var shortname =  object['task'][0]['location']['short_name'];
                        var tasktime = object['task'][0]['task_time'];
                        //alert(tasktime);
                        
                        var taskstatus = object['task'][0]['task_status'];
                        var tasktypeid = object['task'][0]['task_type_id'];
                        var classname = "";
                        var classtext = "";
                        var tasktype = "";
                        var pickupclass = "";
                        
                        if(taskstatus==0)
                        {
                            classtext = "Unassigned";
                            classname = "assign_";
                        }else if(taskstatus==1)
                        {
                            classtext = "Assigned";
                            classname = "assign_";
                        }else if(taskstatus==2)
                        {
                            classtext = "Started";
                            classname = "yellow_";
                        }else if(taskstatus==3)
                        {
                            classtext = "Arrived";
                            classname = "light_green";
                        }else if(taskstatus==4)
                        {
                            classtext = "Completed";
                            classname = "green_";
                        }else{
                            classtext = "Failed";
                            classname = "red_";
                        }

                        if(tasktypeid==1)
                        {
                            tasktype = "Pickup";
                            pickupclass = "yellow_";
                        }else if(tasktypeid==2)
                        {
                            tasktype = "Dropoff";
                            pickupclass = "green_";
                        }else{
                            tasktype = "Appointment";
                            pickupclass = "assign_";
                        }

                        // var sidebarhtml   = '<div class="card-body"><div class="p-2 assigned-block"><div><div class="row no-gutters align-items-center"><div class="col-9 d-flex"><h5 class="d-inline">'+tasktime+'</h5><h6 class="d-inline"><img class="vt-top" src="{{ asset("demo/images/ic_location_blue_1.png") }}">'+location_address+'<span class="d-block">'+shortname+'</span></h6></div><div class="col-3"><button class="assigned-btn float-right mb-2 '+pickupclass+'">'+tasktype+'</button><button class="assigned-btn float-right '+classname+'">'+classtext+'</button></div></div></div></div></div>';
                        var sidebarhtml   = '<div class="card-body ui-sortable-handle" task_id="'+task_id+'"><div class="p-2 assigned-block"><div><div class="row no-gutters align-items-center"><div class="col-9 d-flex"><h5 class="d-inline-flex align-items-center justify-content-between"><i class="fas fa-bars"></i><span>'+tasktime+'</span></h5><h6 class="d-inline"><img class="vt-top" src="{{ asset("demo/images/ic_location_blue_1.png") }}">'+location_address+'<span class="d-block">'+shortname+'</span></h6></div><div class="col-3"><button class="assigned-btn float-right mb-2 '+pickupclass+'">'+tasktype+'</button><button class="assigned-btn float-right '+classname+'">'+classtext+'</button></div></div></div></div></div>';
                        //$('#collapse'+agentid).append(sidebarhtml);
                        $('#handle-dragula-left'+agentid).append(sidebarhtml);
                    }

                    // -------- for route show ------------------
                    reInitMap(data.allroutedata);    

                    var params = "'"+taskids+"','"+distancematrix+"','',"+agentid;
                    var funperams = '<span class="optimize_btn" onclick="RouteOptimization('+params+')">Optimize</span>';                    
                    $('.optimizebtn'+agentid).html(funperams);
                               

                    // const directionsService = new google.maps.DirectionsService();
                    // const directionsRenderer = new google.maps.DirectionsRenderer({suppressMarkers: true});
                    // directionsRenderer.setOptions({
                    //     polylineOptions: {
                    //         strokeColor: 'black'                
                    //     }
                    // });
                    // directionsRenderer.setMap(map);
                    // var allroutes = data.routedata;            
                    // var agnt_task = allroutes.task_details;
                    // var agnt_locatn = allroutes.driver_detail;            
                    // calculateAndDisplayRoute(directionsService, directionsRenderer,map,agnt_task,agnt_locatn);

                    // ----- route show end-----------

                    $('.pageloader').css('display','none');
                    //location.reload();
                }else{                    
                    alert(response);
                    $('.pageloader').css('display','none');
                }
            },
            error: function(response) {
                
            }
        });
    }else{
        $('.pageloader').css('display','none');
    }
    
}



function reInitMap(allroutes) {
    
    const haightAshbury = {
        // lat: allagent[0].agentlog && allagent[0].agentlog['lat']  != "0.00000000" ? parseFloat(allagent[0].agentlog['lat']): 30.7046,
        // lng: allagent[0].agentlog && allagent[0].agentlog['long'] != "0.00000000" ? parseFloat(allagent[0].agentlog['long']):76.7179
        lat: allagent[0].agentlog && allagent[0].agentlog['lat']  != "0.00000000" ? parseFloat(allagent[0].agentlog['lat']): defaultlat,
        lng: allagent[0].agentlog && allagent[0].agentlog['long'] != "0.00000000" ? parseFloat(allagent[0].agentlog['long']):defaultlong
    };

    map = new google.maps.Map(document.getElementById("map_canvas"), {
        zoom: 12,
        center: haightAshbury,
        mapTypeId: "roadmap",
        styles: themeType,
    });
   
    //new code for route
    var color = [
        "blue",
        "green",
        "red",
        "purple",
        "skyblue",
        "yellow",
        "orange",
        
        ];

    //var allroutes = {!! json_encode($routedata) !!};
    $.each(allroutes, function(i, item) {
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({suppressMarkers: true});
        if(i < color.length)
        {
            var routecolor = color[i];
        }else{
            var routecolor = "pink";
        }
        
        directionsRenderer.setOptions({
            polylineOptions: {
                strokeColor: routecolor                
            }
        });
        directionsRenderer.setMap(map);

        var al_task = allroutes[i].task_details;
        var agent_locatn = allroutes[i].driver_detail;
        calculateAndDisplayRoute(directionsService, directionsRenderer,map,al_task,agent_locatn);
    });

    // Adds a marker at the center of the map.
    for (let i = 0; i < olddata.length; i++) {
        checkdata = olddata[i];

        var urlnewcreate = '';
        if(checkdata['task_status'] == 0){
            urlnewcreate = 'unassigned';
        }else if(checkdata['task_status'] == 1 || checkdata['task_status'] == 2){
            urlnewcreate = 'assigned';
        }else if(checkdata['task_status'] == 3){
            urlnewcreate = 'complete';
        }else{
            urlnewcreate = 'faild';
        }
        
        if(checkdata['task_type_id'] == 1){
                urlnewcreate += '_P.png';
        }else if(checkdata['task_type_id'] == 2){
                urlnewcreate +='_D.png';
        }else{
                urlnewcreate +='_A.png';
        }
            
        img = '{{ asset('assets/newicons/') }}'+'/'+urlnewcreate;
        
        send = null;
            type = 1;
        addMarker({
            lat: parseFloat(checkdata['latitude']),
            lng:  parseFloat(checkdata['longitude'])
        }, send, img,checkdata,type);
    }

    //agents markers
    for (let i = 0; i < allagent.length; i++) {
            displayagent = allagent[i];
            
            if(displayagent.agentlog != null && displayagent.agentlog['lat'] != "0.00000000" && displayagent.agentlog['long'] != "0.00000000" ){
                console.log(displayagent.agentlog);
                        if (displayagent['is_available'] == 1) {
                            images = url+'/demo/images/location.png';
                        }else {
                            images = url+'/demo/images/location_grey.png';
                        }
                        var image = {
                        url: images, // url
                        scaledSize: new google.maps.Size(50, 50), // scaled size
                        origin: new google.maps.Point(0,0), // origin
                        anchor: new google.maps.Point(22,22) // anchor
                        };
                        send = null;
                        type = 2;

                        addMarker({lat: parseFloat(displayagent.agentlog['lat']),
                        lng:  parseFloat(displayagent.agentlog['long'])
                        }, send, image,displayagent,type);
            }
            
    }
}

//for drag drop functionality
// jQuery(".dragable_tasks").sortable();
$(".dragable_tasks").sortable({
    update : function(event, ui) {
        $('.routetext').text('Arranging Route');    
        $('.pageloader').css('display','block');        
        var divid = $(this).attr('id');
        var params = $(this).attr('params');
        var agentid = $(this).attr('agentid');       
        
        var taskorder = "";
        jQuery("#"+divid+" .card-body.ui-sortable-handle").each(function (index, element) {
            taskorder = taskorder + $(this).attr('task_id') + ",";
        });

        $.ajax({
            type: 'POST',            
            url: '{{url("/arrange-route")}}',
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            data: {'taskids':taskorder},

            success: function(response) {
                var data = $.parseJSON(response);                                                      
                reInitMap(data.allroutedata);    
                var funperams = '<span class="optimize_btn" onclick="RouteOptimization('+params+')">Optimize</span>';
                $('.optimizebtn'+agentid).html(funperams);
                $('.pageloader').css('display','none');                
            },
            error: function(response) {
                alert('There is some issue. Try again later');
                $('.pageloader').css('display','none');
            }
        });
    }
});

function reloadData() {
    location.reload();
}

function openAllAccordian() {
    $("#accordion").find(`[data-toggle="collapse"]`).removeClass('collapsed');
    $("#accordion").find(`[data-toggle="collapse"]`).attr('aria-expanded','true');
    $(".collapse").addClass('show');    
    $(".allAccordian").html('<span class="" onclick="closeAllAccordian()">Close All</span>');
}

function closeAllAccordian() {
    $("#accordion").find(`[data-toggle="collapse"]`).addClass('collapsed');
    $("#accordion").find(`[data-toggle="collapse"]`).attr('aria-expanded','false');
    $(".collapse").removeClass('show');    
    $(".allAccordian").html('<span class="" onclick="openAllAccordian()">Open All</span>');
}


$('.teamchecks').on('change', function() {
    $('.teamchecks').not(this).prop('checked', false);  
});

$('.taskchecks').on('change', function() {
    $('.taskchecks').not(this).prop('checked', false);  
});

$('.agentcheck').on('change', function() {
    $('.agentcheck').not(this).prop('checked', false);  
});

</script>


<style>
    .gm-style-iw.gm-style-iw-c {
    width: 300px !important;
}
.img_box {
    width: 100%;
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
}

.img_box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.user_name label {
    font-size: 14px;
    color: #000;
    text-transform: capitalize;
    margin: 0 0 12px !important;
    display: block;
}
.user_info i {
    font-size: 14px;
    width: 20px;
    text-align: center;
    color: #6658dd;
}

.user_info span,.user_info b {
    font-size: 12px;
    font-weight: 500;
}

.pageloader {
    width: 100%;
    height: 100%;
   position: absolute;
    top: 40%;
    z-index: 999;
    left: 50%;    
}

.box {
    background: #fff;
    width: 250px;
    height: 170px;
    text-align: center;
    padding: 17px;
    color: blue;
    opacity: 0.9;
    border-radius: 5px;
}

.box h4 {
    color: #3283f6;
}

</style>

@endsection
