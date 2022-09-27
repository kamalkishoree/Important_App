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

        <div id="task_container">
            
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
                    <input type="text"  id="basic-datepicker" class="datetime" value="{{date('Y-m-d', strtotime($date))}}" data-date-format="Y-m-d">

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

$(document).ready(function() {

    $('#wrapper').addClass('dshboard');

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
let color = [
        "blue",
        "green",
        "red",
        "purple",
        "skyblue",
        "yellow",
        "orange",
        ];


$(".datetime").on('change', function postinput(){
    var matchvalue = $(this).val(); 
    loadTeams();
});


// autoload dashbard
function loadTeams(){
    $("#task_container").empty();
    spinnerJS.showSpinner();
    var checkuserstatus = $('input[name="user_status"]:checked').val();
    $.ajax({
        type: 'POST',
        url: "{{ route('dashboard.teamsdata')}}",
        headers: {
            'X-CSRF-Token': '{{ csrf_token() }}',
        },
        data: {'userstatus':checkuserstatus, 'routedate':$("#basic-datepicker").val()},
        success: function(data) {
            $("#task_container").empty();
            $("#task_container").html(data);
            spinnerJS.hideSpinner();
            initializeSortable();
        },
        error: function(data) {
            alert('There is some issue. Try again later');
            spinnerJS.hideSpinner()
        }
    });
    
}


function initializeSortable()
{
    $(".dragable_tasks").sortable({
        update : function(event, ui) {
            $('.routetext').text('Arranging Route');
            spinnerJS.showSpinner();
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
                    //reInitMap(data.allroutedata);
                    reInitMap();
                    $('.totdis'+agentid).html(data.total_distance);
                    var funperams = '<span class="optimize_btn" onclick="RouteOptimization('+params+')">Optimize</span>';
                    $('.optimizebtn'+agentid).html(funperams);
                    spinnerJS.hideSpinner();
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
    });
    
}
// delete agent marks
function deleteAgentMarks() {
    for (let i = 0; i < driverMarkers.length; i++) {
        driverMarkers[i].setMap(null);
    }
}
// for reinitializing map in ajax response during drag drop and optimization
function reInitMap() {
    const haightAshbury = {
        lat: $("#initial_lotitude").val() != "0.00000000" ? parseFloat($("#initial_lotitude").val()): defaultlat,
        lng: $("#initial_longitude").val() != "0.00000000" ? parseFloat($("#initial_longitude").val()):defaultlong
    };
    map = new google.maps.Map(document.getElementById("map_canvas"), {
        zoom: 12,
        center: haightAshbury,
        mapTypeId: "roadmap",
        styles: themeType,
    });

    //new code for route
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
<!-- <script src="{{ asset('js/app.js') }}"></script> -->
<script>
/* Echo.channel('orderdata.{{$client_code}}')
    .listen('loadDashboardData', (e) => {
        var heading = "";
        var message = "";
        var color = "";
        if(typeof(e.order_data.id) != "undefined")
        {
            if(e.order_data.status == "unassigned")
            {
                heading = "Created";
                message = "New Route Created.";
                color = "green";
            }
            else if(e.order_data.status == "assigned")
            {
                heading = "Assigned";
                message = "Route Assigned to {{__(getAgentNomenclature())}}.";
                color = "orange";
            }
            else if(e.order_data.status == "completed")
            {
                heading = "Completed";
                message = "Route Completed by {{__(getAgentNomenclature())}}.";
                color = "orange";
            }
        }
        else
        {
            heading = "Deleted";
            message = "Route Deleted.";
            color = "red";
        }

        if(heading!='')
        {
            $.toast({ 
                heading:heading,
                text : message, 
                showHideTransition : 'slide', 
                bgColor : color,              
                textColor : '#eee',            
                allowToastClose : true,      
                hideAfter : 5000,            
                stack : 5,                   
                textAlign : 'left',         
                position : 'top-right'      
            });

            autoloadmap();
        }
    }) */
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
