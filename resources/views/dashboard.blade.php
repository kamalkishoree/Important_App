@extends('layouts.vertical', ['title' => 'Dashboard','demo'=>'creative'])

@section('css')
<!-- Plugins css -->
<link href="{{ asset('demo/css/style.css') }}" rel="stylesheet" type="text/css" />
@endsection
@php

use Carbon\Carbon;
$color = ['one','two','three','four','five','six','seven','eight'];
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp
@section('content')

<!-- Bannar Section -->
{{-- <section class="bannar header-setting"> --}}
<div class="container-fluid p-0">
<div class="row coolcheck no-gutters">
    {{-- <div class="pageloader" style="display: none;">
        <div class="box">
            <h4 class="routetext"></h4>
            <div class="spinner-border avatar-lg text-primary m-2" role="status"></div>
        </div>
    </div> --}}
    <div id="scrollbar" class="col-md-4 col-xl-3 left-sidebar pt-3">
        <div class="side_head d-flex justify-content-between align-items-center mb-2">
            <i class="mdi mdi-sync mr-1" onclick="reloadData()" aria-hidden="true"></i>
            <div>
                <div class="radio radio-primary form-check-inline ml-3 mr-2">
                    <input type="radio" id="user_status_all" value="2" name="user_status" class="checkUserStatus" checked>
                    <label for="user_status_all"> {{__("All")}} </label>
                </div>
                
                <div class="radio radio-primary form-check-inline">
                    <input type="radio" id="user_status_online" value="1" name="user_status" class="checkUserStatus">
                    <label for="user_status_online"> {{__("Online")}} </label>
                </div>

                <div class="radio radio-info form-check-inline mr-2">
                    <input type="radio" id="user_status_offline" value="0" name="user_status" class="checkUserStatus">
                    <label for="user_status_offline"> {{__("Offline")}} </label>
                </div>
            </div>

            <span class="allAccordian"><span class="" onclick="openAllAccordian()">{{__('Open All')}}</span></span>
        </div>
        <div id="accordion" class="overflow-hidden">
            <div class="card no-border-radius">
                <div class="card-header" id="heading-1">
                    <a role="button" data-toggle="collapse" href="#collapse-new"
                        aria-expanded="false" aria-controls="collapse-new">
                        <div class="newcheckit">
                            <div class="row d-flex align-items-center" class="mb-0">
                                <div class="col-md-4 col-lg-3 col-xl-2 col-2">
                                    <span class="profile-circle">U</span>
                                </div>
                                <div class="col-md-8 col-lg-9 col-xl-10 col-10">
                                    <h6 class="header-title">{{__('Unassigned')}}</h6>
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
                                        $routeperams0 = "'".$distance_matrix[0]['tasks']."','".json_encode($distance_matrix[0]['distance'])."','".$opti0."',0,'".$date."'";
                                        $optimize0 = '<span class="optimize_btn" onclick="RouteOptimization('.$routeperams0.')">'.__("Optimize").'</span>';
                                        $params0 = "'".$distance_matrix[0]['tasks']."','".json_encode($distance_matrix[0]['distance'])."','yes',0,'".$date."'";
                                        $turnbyturn0 = '<span class="navigation_btn optimize_btn" onclick="NavigatePath('.$routeperams0.')">'.__("Export").'</span>';
                                    }else{
                                        $optimize0="";
                                        $params0 = "";
                                        $turnbyturn0 = "";
                                    }
                                    ?>
                                    <a class="profile-block collapsed" role="button" data-toggle="collapse" href="#collapse0" aria-expanded="false" aria-controls="collapse0">
                                        <div class="row">
                                            <div class="col-md-2 col-2">
                                                <span class="profile-circle">D</span>
                                            </div>
                                            <div class="col-md-10 col-10">
                                                <h6 class="mb-0 header-title scnd">{{__("Unassigned Tasks")}}<div  class="optimizebtn0">{!! $optimize0 !!} </div><div class="exportbtn0">{!! $turnbyturn0 !!} </div></h6>
                                                <p class="mb-0"> <span>{{ count($unassigned_orders) }} {{__("Tasks")}}</span> {!! $unassigned_distance==''?'':' <i class="fas fa-route"></i> '!!}<span class="dist_sec totdis0 ml-1">{{ $unassigned_distance }}</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div id="collapse0" class="collapse" data-parent="#accordion-0" aria-labelledby="by0">
                                    <div id="handle-dragula-left0" class="dragable_tasks" agentid="0"  params="{{ $params0 }}" date="{{ $date }}">
                                        @foreach($unassigned_orders as $orders)
                                            @foreach($orders['task'] as $tasks)
                                                <div class="card-body" task_id ="{{ $tasks['id'] }}">
                                                    <div class="p-2 assigned-block">
                                                        @php
                                                            $st ="Unassigned";
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
                                                                    @php
                                                                    if($tasks['assigned_time']=="")
                                                                    {
                                                                        $tasks['assigned_time'] = date('Y-m-d H:i:s');
                                                                    }
                                                                        $timeformat = $preference->time_format == '24' ? 'H:i:s':'g:i a';
                                                                        $order = Carbon::createFromFormat('Y-m-d H:i:s', $tasks['assigned_time'], 'UTC');

                                                                        //$order->setTimezone(isset(Auth::user()->timezone) ? Auth::user()->timezone : 'Asia/Kolkata');
                                                                        $order->setTimezone($client_timezone);
                                                                    @endphp

                                                                    <h5 class="d-inline-flex align-items-center justify-content-between"><i class="fas fa-bars"></i> <span>{{date(''.$timeformat.'', strtotime($order))}}</span></h5>
                                                                    <h6 class="d-inline"><img class="vt-top"
                                                                        src="{{ asset('demo/images/ic_location_blue_1.png') }}"> {{ isset($tasks['location']['address'])? $tasks['location']['address']:'' }} <span class="d-block">{{ isset($tasks['location']['short_name'])? $tasks['location']['short_name']:'' }}</span></h6>
                                                                </div>
                                                                <div class="col-3">
                                                                    <button class="assigned-btn float-right mb-2 {{$pickup_class}}">{{__($tasktype)}}</button>
                                                                    <button class="assigned-btn float-right {{$color_class}}">{{__($st)}}</button>
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
            <div class="card no-border-radius" id="teams_container">


                
            </div>
        </div>

        <form id="pdfgenerate" method="post" enctype="multipart/form-data" action="{{ route('download.pdf') }}">
            @csrf
            <input id="pdfvalue" type="hidden" name="pdfdata">
        </form>

    </div>

    <div class="col-md-8 col-xl-9">
        <div class="map-wrapper">
            <div style="width: 100%">
                <div id="map_canvas" style="width: 100%; height:calc(100vh - 70px);"></div>
            </div>
            <div class="contant">
                <div class="bottom-content">
                    <input type="text"  id="basic-datepicker" class="datetime brdr-1" value="{{date('Y-m-d', strtotime($date))}}" data-date-format="Y-m-d">

                    <div class="dropdown d-inline-block">
                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="mr-1" src="{{ asset('demo/images/ic_time.png') }}">{{__(getAgentNomenclature().'s')}}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <div class="task-block pl-2 pr-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span>{{__(getAgentNomenclature().'s')}}</span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href=""><span></span></a>
                                        <a class="ml-3" href=""><span></span></a>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-8">
                                        <h6>{{__('All '.getAgentNomenclature().'s')}}</h6>
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
                                        <h6><span class="circle lia-castro mr-2"></span>{{__('Online')}}</h6>
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
                                        <h6><span class="circle mr-2"></span>{{__('Offline')}}</h6>
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
                    <div style="display:none">
                        <input class="newchecks filtercheck teamchecks" cla type="checkbox" value="-1" name="teamchecks[]" checked>
                        <input class="taskchecks filtercheck" type="checkbox" name="taskstatus[]" value="5" checked>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection
@include('modals.optimize-route')

<?php   // for setting default location on map
    $agentslocations = array();
    if(!empty($agents)){
        foreach ($agents as $singleagent) {
            if((!empty($singleagent['agentlog'])) && ($singleagent['agentlog']['lat']!=0) && ($singleagent['agentlog']['long']!=0))
            {
                $agentslocations[] = $singleagent['agentlog'];
            }
        }
    }

    // $defaultmaplocation['lat'] = 30.7046;
    // $defaultmaplocation['long'] = 76.7179;
    $defaultmaplocation['lat'] = $defaultCountryLatitude;
    $defaultmaplocation['long'] = $defaultCountryLongitude;
    $agentslocations[] = $defaultmaplocation;
?>

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.6.3/jquery.timeago.js"></script>
<script>

$('.teamchecks').on('change', function() {
    $('.teamchecks').not(this).prop('checked', false);
});

$('.taskchecks').on('change', function() {
    $('.taskchecks').not(this).prop('checked', false);
});

$('.agentcheck').on('change', function() {
    $('.agentcheck').not(this).prop('checked', false);
});

$(document).ready(function() {

    $('#wrapper').addClass('dshboard');

    initMap();

    $('#shortclick').trigger('click');
    $(".timeago").timeago();

    $('.checkUserStatus').click(function() {
        loadTeams();
    });
    loadTeams();
});

function gm_authFailure() {

    $('.excetion_keys').append('<span><i class="mdi mdi-block-helper mr-2"></i> <strong>Google Map</strong> key is not valid</span><br/>');
    $('.displaySettingsError').show();
};

// var marker;
var show = [0];
let map;
let markers = [];
let driverMarkers = [];
let privesRoute = [];

var url = window.location.origin;
var olddata  = {!!json_encode($newmarker)!!};
var allagent = {!!json_encode($agents)!!};

// for getting default map location
var defaultmaplocation = {!!json_encode($agentslocations)!!};
var defaultlat = parseFloat(defaultmaplocation[0].lat);
var defaultlong = parseFloat(defaultmaplocation[0].long);

var imgproxyurl         = {!!json_encode($imgproxyurl)!!};


$('.filtercheck').click(function() {
    $('.agentcheck').not(this).prop('checked', false);
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
//main task markers
for (let i = 0; i < olddata.length; i++) {
    checkdata = olddata[i];
    var info = []
    
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


$('.taskchecks_old').click(function() {
var taskval = [];
$('.taskchecks:checkbox:checked').each(function(i) {
    taskval[i] = parseInt($(this).val());
});

setMapOnAll(null);
$(".newchecks").prop('checked', false);
$(".agentdisplay").prop('checked', false);

for (let i = 0; i < olddata.length; i++) {
    checkdata = olddata[i];

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

$('.agentdisplay_old').click(function() {
    var agentval = [];
    $('.agentdisplay:checkbox:checked').each(function(i) {
        agentval[i] = parseInt($(this).val());
    });
    setMapOnAll(null);
    $(".taskchecks").prop('checked', false);
    $(".newchecks").prop('checked', false);

    for (let i = 0; i < allagent.length; i++) {
        checkdata = allagent[i];
        
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
    const haightAshbury = {
        lat: allagent.length != 0 && allagent[0].agentlog && allagent[0].agentlog['lat']  != "0.00000000" ? parseFloat(allagent[0].agentlog['lat']): defaultlat,
        lng: allagent.length != 0 && allagent[0].agentlog && allagent[0].agentlog['long'] != "0.00000000" ? parseFloat(allagent[0].agentlog['long']):defaultlong
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

    setTimeout(function() {
            autoloadmap();
    }, 5000);
}

// function for displaying route  on map
function calculateAndDisplayRoute(directionsService, directionsRenderer,map,alltask,agent_location) {
            const waypts = [];
            const checkboxArray = document.getElementById("waypoints");

            for (let i = 0; i < alltask.length; i++) {
                if (i != alltask.length - 1 && alltask[i].task_status != 4 && alltask[i].task_status != 5 ) {
                    waypts.push({
                        location: new google.maps.LatLng(parseFloat(alltask[i].latitude), parseFloat(alltask[i]
                            .longitude)),
                        stopover: true,
                    });

                }
                var image = url+'/assets/newicons/'+alltask[i].task_type_id+'.png';
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
                    //animation: google.maps.Animation.DROP,
                });
    if (type == 2) {
        driverMarkers.push(marker)
    }

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
    var matchvalue = $(this).val(); 
    newabc =  url+'?date='+matchvalue;
    window.location.href = newabc;
});

//function fot optimizing route
function RouteOptimization(taskids,distancematrix,optimize,agentid,date) {
    $('#routeTaskIds').val(taskids);
    $('#routeMatrix').val(distancematrix);
    $('#routeOptimize').val(optimize);
    $('#routeAgentid').val(agentid);
    $('#routeDate').val(date);
    $('#optimizeType').val('optimize');
    $("input[name='driver_start_location'][value='current']").prop("checked",true);
    $('#addressBlock').css('display','none');
    $('#addressTaskBlock').css('display','none');
    $('#selectedtasklocations').html('');
    $('.selecttask').css('display','');
    $.ajax({
            type: 'POST',
            url: '{{url("/get-tasks")}}',
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            data: {'taskids':taskids},

            success: function(response) {

                var data = $.parseJSON(response);

                for (var i = 0; i < data.length; i++) {
                    var object = data[i];
                    var task_id =  object['id'];
                    var tasktypeid = object['task_type_id'];
                    var current_location = object['current_location'];
                    if(current_location == 0)
                    {
                        $('input[type=radio][name=driver_start_location]').prop('checked', false);
                        $("input[type=radio][name=driver_start_location][value='current']").remove();
                        $("#radio-current-location-span").remove();
                        $("input[type=radio][name=driver_start_location][value='select']").click();
                    }

                    if(tasktypeid==1)
                    {
                        tasktype = "Pickup";
                    }else if(tasktypeid==2)
                    {
                        tasktype = "Dropoff";
                    }else{
                        tasktype = "Appointment";
                    }

                    var location_address =  object['location']['address'];
                    var shortname =  object['location']['short_name'];


                    var option   = '<option value="'+task_id+'">'+tasktype+' - '+shortname+' - '+location_address+'</option>';
                    $('#selectedtasklocations').append(option);
                }
            },
            error: function(response) {
            }
        });

        $('#optimize-route-modal').modal('show');
}


// on submiting optimization popup
$('.submitoptimizeForm').click(function(){
    var driverStartTime = $('.driverStartTime').val();
    var driverTaskDuration = $('.driverTaskDuration').val();
    var driverBrakeStartTime = $('.driverBrakeStartTime').val();
    var driverBrakeEndTime = $('.driverBrakeEndTime').val();
    var sortingtype = $('#optimizeType').val();
    var err = 0;
    if(driverStartTime=='')
    {
        $('#DriverStartTime span').css('display','block');
        err = 1;
    }

    if(driverTaskDuration=='')
    {
        $('#DriverTaskDuration span').css('display','block');
        err = 1;
    }
    if(driverBrakeStartTime=='')
    {
        $('#DriverBrakeStartTime span').css('display','block');
        err = 1;
    }

    if(driverBrakeEndTime=='')
    {
        $('#DriverBrakeEndTime span').css('display','block');
        err = 1;
    }

    if(err == 0)
    {
        $('.routetext').text('Optimizing Route');
        $('#optimize-route-modal').modal('hide');
        //$('.pageloader').css('display','block');
        spinnerJS.showSpinner()
        var formdata =$('form#optimizerouteform').serialize();
        if(sortingtype=='optimize')
        {
            var formurl = '{{url("/optimize-route")}}';
        }else{
            var formurl = '{{url("/optimize-arrange-route")}}';
        }
        $.ajax({
                type: 'POST',
                url: formurl,
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                data : formdata,
                success: function(response) {
                    if(response!="Try again later")
                    {
                        var data = $.parseJSON(response);
                        var tasklist = data.tasklist;
                        var taskorders = tasklist.order;
                        var agentid = data.agentid;
                        var taskids = data.taskids;
                        var distancematrix = data.distance_matrix;
                        var date = data.date;
                        $('.totdis'+agentid).html(data.total_distance);
                        $('#handle-dragula-left'+agentid).html('');
                        for (var i = 0; i < taskorders.length; i++) {
                            var object = taskorders[i];
                            var task_id =  object['task'][0]['id'];
                            var location_address =  object['task'][0]['location']['address'];
                            var shortname =  object['task'][0]['location']['short_name'];
                            var tasktime = object['task'][0]['task_time'];
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
                            var sidebarhtml   = '<div class="card-body ui-sortable-handle" task_id="'+task_id+'"><div class="p-2 assigned-block"><div><div class="row no-gutters align-items-center"><div class="col-9 d-flex"><h5 class="d-inline-flex align-items-center justify-content-between"><i class="fas fa-bars"></i><span>'+tasktime+'</span></h5><h6 class="d-inline"><img class="vt-top" src="{{ asset("demo/images/ic_location_blue_1.png") }}">'+location_address+'<span class="d-block">'+shortname+'</span></h6></div><div class="col-3"><button class="assigned-btn float-right mb-2 '+pickupclass+'">'+tasktype+'</button><button class="assigned-btn float-right '+classname+'">'+classtext+'</button></div></div></div></div></div>';
                            $('#handle-dragula-left'+agentid).append(sidebarhtml);
                        }

                        if(sortingtype=='optimize')
                        {
                            // -------- for route show ------------------
                            reInitMap(data.allroutedata);
                            var params = "'"+taskids+"','"+distancematrix+"','',"+agentid+",'"+date+"'";
                            var funperams = '<span class="optimize_btn" onclick="RouteOptimization('+params+')">Optimize</span>';
                            $('.optimizebtn'+agentid).html(funperams);

                            // ----- route show end-----------
                        }

                        $('#optimizerouteform').trigger("reset");
                        //$('.pageloader').css('display','none');
                        spinnerJS.hideSpinner()
                        //location.reload();
                    }else{
                        alert(response);
                        //$('.pageloader').css('display','none');
                        spinnerJS.hideSpinner()
                    }
                },
                error: function(response) {

                }
            });
    }

});

function cancleForm()
{
    $('#optimizerouteform').trigger("reset");
    $('#optimize-route-modal').modal('hide');
}
// autoload dashbard
function loadTeams(){
    $("#teams_container").empty();
    //$("#teams_container").html('<div class="spinner-border text-blue m-2" role="status"></div>');
    spinnerJS.showSpinner();
    var route_teams_data = "{{ route('dashboard.teamsdata', ':id') }}";
    var checkuserstatus = $('input[name="user_status"]:checked').val();
    route_teams_data = route_teams_data.replace(":id", checkuserstatus);
    $.get(route_teams_data, function(data) {
        $("#teams_container").empty();
        $("#teams_container").html(data);
        spinnerJS.hideSpinner()
    });
}

function autoloadmap(){
    var route_dashboard_data = "{{ route('dashboard.data', ':id') }}";
    var checkuserstatus = $('input[name="user_status"]:checked').val();
    route_dashboard_data = route_dashboard_data.replace(":id", checkuserstatus);
    $.get(route_dashboard_data, function(data) {

        allagent = data.data.agents;
        allroutes = data.data.routedata;
        olddata =  data.data.newmarker;
        teams =  data.data.teams;


        var color = [
            "blue",
            "green",
            "red",
            "purple",
            "skyblue",
            "yellow",
            "orange",
        ];


        deleteAgentMarks();
        //teamupdate markers
        for (let j = 0; j < teams.length; j++) {
                var agent_count = teams[j]['agents_count'];
                var team_online_agent_count = teams[j]['online_agents'];
                var team_offline_agent_count = teams[j]['offline_agents'];

                $("#team_agent_"+ teams[j]['id']).text(agent_count);
                $("#team_online_agent_"+ teams[j]['id']).text(team_online_agent_count);
                $("#team_offline_agent_"+ teams[j]['id']).text(team_offline_agent_count);

            let teamAgents = teams[j]['agents'];
            for (let a = 0; a < teamAgents.length; a++) {

                var agent_onlineStatus = ( teamAgents[a]['is_available']  == 1 ) ? ' ({{ __("Online") }})' : ' ({{__("Offline")}})' ;
                    console.log("#tram_agent_online_status_"+ teamAgents[a]['id']);
                    console.log(agent_onlineStatus);
                    $("#tram_agent_online_status_"+teamAgents[a]['id']).text(agent_onlineStatus);
            }


        }

        for (let i = 0; i < allagent.length; i++) {
            displayagent = allagent[i];

            if (displayagent.agentlog != null && displayagent.agentlog['lat'] != "0.00000000" && displayagent.agentlog[
                    'long'] != "0.00000000") {
                
                if (displayagent['is_available'] == 1) {
                    images = url + '/demo/images/location.png';
                } else {
                    images = url + '/demo/images/location_grey.png';
                }
                var image = {
                    url: images, // url
                    scaledSize: new google.maps.Size(50, 50), // scaled size
                    origin: new google.
                    maps.Point(0, 0), // origin
                    anchor: new google.maps.Point(22, 22) // anchor
                };
                send = null;
                type = 2;

                addMarker({
                    lat: parseFloat(displayagent.agentlog['lat']),
                    lng: parseFloat(displayagent.agentlog['long'])
                }, send, image, displayagent, type);
            }

        }
        setTimeout(function() {
            autoloadmap();
        }, 5000);
    });
    
}
// delete agent marks
function deleteAgentMarks() {
    for (let i = 0; i < driverMarkers.length; i++) {
        driverMarkers[i].setMap(null);
    }
}
// for reinitializing map in ajax response during drag drop and optimization
function reInitMap(allroutes) {
    const haightAshbury = {
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
$(".dragable_tasks").sortable({
    update : function(event, ui) {
        $('.routetext').text('Arranging Route');
        spinnerJS.showSpinner()
        //$('.pageloader').css('display','block');
        var divid = $(this).attr('id');
        var params = $(this).attr('params');
        var agentid = $(this).attr('agentid');
        var date = $(this).attr('date');

        var taskorder = "";
        jQuery("#"+divid+" .card-body.ui-sortable-handle").each(function (index, element) {
            taskorder = taskorder + $(this).attr('task_id') + ",";
        });
        $('input[type=radio][name=driver_start_location]').prop('checked', false);
        $.ajax({
            type: 'POST',
            url: '{{url("/arrange-route")}}',
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            data: {'taskids':taskorder,'agentid':agentid,'date':date},

            success: function(response) {
                var data = $.parseJSON(response);
                reInitMap(data.allroutedata);
                $('.totdis'+agentid).html(data.total_distance);
                var funperams = '<span class="optimize_btn" onclick="RouteOptimization('+params+')">Optimize</span>';
                $('.optimizebtn'+agentid).html(funperams);
                //$('.pageloader').css('display','none');
                spinnerJS.hideSpinner()
                $('#routeTaskIds').val(taskorder);
                $('#routeMatrix').val('');
                $('#routeOptimize').val('');
                $('#routeAgentid').val(agentid);
                $('#routeDate').val(date);
                $('#optimizeType').val('dragdrop');
                $("input[name='driver_start_location'][value='current']").prop("checked",true);
                $('#addressBlock').css('display','none');
                $('#addressTaskBlock').css('display','none');
                $('#selectedtasklocations').html('');
                $('.selecttask').css('display','none');
                
                if(data.current_location == 0)
                {
                    $("input[type=radio][name=driver_start_location][value='current']").remove();
                    $("#radio-current-location-span").remove();
                    $("input[type=radio][name=driver_start_location][value='select']").click();
                }
                $('#optimize-route-modal').modal('show');
            },
            error: function(response) {
                alert('There is some issue. Try again later');
                //$('.pageloader').css('display','none');
                spinnerJS.hideSpinner()
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

function NavigatePath(taskids,distancematrix,optimize,agentid,date) {
    $('.routetext').text('Exporting Pdf');
    //$('.pageloader').css('display','block');
    spinnerJS.showSpinner()

        $.ajax({
            type: 'POST',
            url: '{{url("/export-path")}}',
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            data: {'taskids':taskids,'agentid':agentid,'date':date},

            success: function(response) {
                if(response!="Try again later")
                {
                    $('#pdfvalue').val(response);
                    $("#pdfgenerate").submit();
                    spinnerJS.hideSpinner()
                    //$('.pageloader').css('display','none');
                }else{
                    alert(response);
                    spinnerJS.hideSpinner()
                    //$('.pageloader').css('display','none');
                }
            },
            error: function(response) {

            }
        });


}

$('input[type=radio][name=driver_start_location]').change(function() {
    if (this.value == 'current') {
        $('#addressBlock').css('display','none');
        $('#addressTaskBlock').css('display','none');
    }
    else if (this.value == 'select') {
        $('#addressBlock').css('display','block');
        $('#addressTaskBlock').css('display','none');
    }
    else if(this.value == 'task_location') {
        $('#addressTaskBlock').css('display','block');
        $('#addressBlock').css('display','none');
    }
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
