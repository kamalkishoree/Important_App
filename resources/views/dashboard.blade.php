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
                                                                src="{{isset($agent['profile_picture']) ? $imgproxyurl.Storage::disk('s3')->url($agent['profile_picture']):'https://dummyimage.com/36x36/ccc/fff'}}">
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
                                            <input class="newchecks filtercheck" cla type="checkbox" value="-1"
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
                                            <input class="newchecks filtercheck" cla type="checkbox" value="0"
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
                                                <input class="newchecks filtercheck" type="checkbox" name="teamchecks[]"
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
                                            <input class="agentdisplay filtercheck" type="checkbox" name="agentcheck[]" value="2">
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
                                            <input class="agentdisplay filtercheck" type="checkbox" name="agentcheck[]" value="1">
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
                                            <input class="agentdisplay filtercheck" type="checkbox" name="agentcheck[]" value="0">
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


{{-- <script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/clockpicker/clockpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

<!-- Page js-->
<script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script> --}}
{{-- <script src="{{ asset('demo/js/propeller.min.js') }}"></script>
--}}
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
    //console.log(checkdata);
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

    console.log(allagent);
    const haightAshbury = {
        lat: allagent[0].agentlog && allagent[0].agentlog['lat']  != "0.00000000" ? parseFloat(allagent[0].agentlog['lat']): 30.7046,
        lng: allagent[0].agentlog && allagent[0].agentlog['long'] != "0.00000000" ? parseFloat(allagent[0].agentlog['long']):76.7179
    };

    map = new google.maps.Map(document.getElementById("map_canvas"), {
        zoom: 12,
        center: haightAshbury,
        mapTypeId: "roadmap",
        styles: themeType,
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


// Adds a marker to the map and push to the array.
function addMarker(location, lables, images,data,type) {

    var contentString = '';
    images
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
        '<div style="width:48%;display:inline-block">'+
        '<img src="https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/'+data['image_url']+'">'+
        "</div>"+
        '<div style="width:48%;display:inline-block;vertical-align:middle;margin-left:5px;"><b>'+data['name']+'</b><br/><br/>'+data['phone_number']+'</div>'+
        '<div style="margin-top:8px;"><b><img src="{{ asset("demo/images/clock.png") }}"> : '+jQuery.timeago(new Date(data['agentlog']['created_at']))+'</b><br/><br/><img src="{{ asset("demo/images/operating-system.png") }}"> : '+data['agentlog']['os_version']+'</div>'+
        '<div style="float:left;"><b> <img src="{{ asset("demo/images/battery-status.png") }}"> :  '+data['agentlog']['battery_level']+'%</b><br/>      <br/></div>';
    }



    const infowindow = new google.maps.InfoWindow({
        content: contentString,
        Width: 250,
        maxheight: 250,
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

</script>

@endsection
