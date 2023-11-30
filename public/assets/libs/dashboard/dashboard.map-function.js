var themeType = [{
    featureType: "poi",
    elementType: "labels",
    stylers: [{
        visibility: "off"
    }]
}];
var style= [];
var mark = [];
var agent_mark = [];
var extended_map = 0;
var latlongs;
var show = [0];
var order_tag=[];
var marker = null;
let map;
let marks =[];
let markers = [];
let driverMarkers = [];
let privesRoute = [];
let directionsArray = [];
var is_initMap = true;
let url = window.location.origin;
var directionDisplay;
var directionsService;
var stepDisplay;
var position;
var polyline = null;
var poly2 = null;
var speed = 0.000005, wait = 1;
var infowindow = null;
var directionsService;
var directionsRenderer;

var myPano;   
var panoClient;
var nextPanoId;
var timerHandle = null;

var show = [0];
let olddata  = [];
let allagent = [];

// for getting default map location
let defaultmaplocation = [];
let defaultlat  = 0.000;
let defaultlong = 0.000;
let allroutes = [];
let old_channelname = old_logchannelname = '';
var newicons = '';
  

function handleTeamData(result) {
    olddata = allagent = defaultmaplocation = [];
    //if Html is required to load or not, for agent's log it is not required

    if (is_load_html == 1) {
        $("#teams_container").empty();
        $("#teams_container").html(result);

        if (is_show_loader == 1) {
            spinnerJS.hideSpinner();
        }
        initializeSortable();

        if ($("#newmarker_map_data").val() != '') {
            olddata = JSON.parse($("#newmarker_map_data").val());
        }

        if ($("#agents_map_data").val() != '') {
            allagent = JSON.parse($("#agents_map_data").val());
        }

        if ($("#uniquedrivers_map_data").val() != '') {
            allroutes = JSON.parse($("#uniquedrivers_map_data").val());
        }

        if ($("#agentslocations_map_data").val() != '') {
            defaultmaplocation = JSON.parse($("#agentslocations_map_data").val());
            defaultlat = parseFloat(defaultmaplocation[0].lat);
            defaultlong = parseFloat(defaultmaplocation[0].long);
        }
    } else {
        var data1 = JSON.parse(result);
        if (data1['status'] ==
            "success") {
            
            // setting up required variables to refreshing the google map route
            teams = data1['teams'][0];
            olddata = data1['newmarker'];
            allagent = data1['agents'];
            allteams = data1['teams'];
            unassigned_task=data1['unassigned'];
            latlongs = maplatLong(data1['agents']);
            $("#freeagent").empty();
            $('#busyagents,#completed_tab1').hide();
            $('#busy').text(teams.busy_agents);
            $('#incative').text(teams.offline_agents);
            $('#all').text(teams.online_agents);
            $('#teammenu,#menu,#agentmenu').empty();
            jQuery.each(allagent, function (index, element) {
                // $("#freeagent").append(
                //     '<div class="alTabBoxOuter p-2 agent_detail" id="agent_tab_'+element.id+'" agent_value= "' +
                //     element.id +
                //     '" > <div class="d-flex align-items-center justify-content-between"> <div class="d-flex align-items-start justify-content-between"> <div class="me-2 position-relative"> <span class="alAgentFirtsLetter fs-3 bg-primary rounded-circle text-white">' +
                //     element.name.substr(0, 1) +
                //     '</span> <span class="position-absolute bottom-0 start-100 translate-middle p-1 .bg-primary rounded-circle"></span> </div> <div class="alAgentsDetails w-100"> <div class="d-flex align-items-start justify-content-around"> <div class="me-2"> <b class="alAgentsNameBox text-truncate"><span class="orderId"></span><span class="alAgentsName">' +
                //     element.agent_id +
                //     '</span></b> <b class="alAgentsNameBox text-truncate"><span class="orderId"></span><span class="alAgentsName">' +
                //     element.name +
                //     '</span></b>  <span class="alAgentsPhone d-block">' + element
                //         .phone_number +
                //     '</span> <ul class="p-0 m-0 d-flex alAgentOtherInfo"> <li class="me-3"><span class="alAgentVehicle pe-1 me-1 border-end">' +
                //     element.vehicle_type.name +
                //     '</span> <span class="alAgentTransaction"> Cash</span> </li> <li><span class="alAgentTimeTake">7 minutes</span></li> </ul> </div> <div class="alAgentsChatBox d-flex"> <span style="margin-top: -8px;"><i class="mdi-message-processing mdi mdi-24px text-primary"></i></span> <span class="alAgentsTaskBox text-center"><span class="alAgentsTaskInner rounded-circle border border-light px-1 py-0 mb-2">0</span> <small> Task </small></span> </div> </div> </div> </div> <div class="text-right"> <span><i style="font-size: 30px;" class="uil-angle-right agent_detail" agent_value=' +
                //     element.id +
                //     '></i></span> </div> </div> <hr class="my-2"> </div>'

                // )

                $("#freeagent").append(agent_html(element));
                $("#menu").append(
                    '<a class="dropdown-item agent_detail" agent_value="' + element
                        .id + '" href="#">' + element.name + ' ('+element.agent_id+')</a>'
                )

                $('#agentmenu').append('<option value=' + element.id + '>' + element
                    .name + '        <span>Idle</span></option>')

            })
          
            jQuery.each(allteams, function (index, element) {
                $("#teammenu").append(
                    '<a class="dropdown-item team_detail" href="#" team_value="'+element.id+'">' + element.name + '</a>'
                )
                $('#teammenus').append('<option value=' + element.id + '>' + element
                    .name + '</option>')


            })
            allroutes = data1['routedata'];
            defaultlat = parseFloat(data1['defaultCountryLatitude']);
            defaultlong = parseFloat(data1['defaultCountryLongitude']);
        }
    }

  
    const output = mapData(data1['data']);

    var cluster_filter = $('.dark_theme2').val();
    // if(cluster_filter=='1'){
    //     specificMap(pending_task); 
    // }
    const latlong = maplatLong(data1['agent_log']);
    var agent_location = $('.dark_theme3').val();
    if(refresh ==0){
      
        map = checkInitMap(is_initMap);

    }
    if (agent_location == '1') {
        AgentLocationMap(latlong)
    }
     specificMap(output)
    
}



async function checkInitMap(is_initMap) {
    if (is_initMap) {
        var dark_theme = $(".dark_theme5").val();
        if (extended_map == 1) {
            if (dark_theme == "1") {
                style = [
                    {
                        elementType: "geometry",
                        stylers: [{ color: "#242f3e" }],
                    },
                    {
                        elementType: "labels.text.stroke",
                        stylers: [{ color: "#242f3e" }],
                    },
                    {
                        elementType: "labels.text.fill",
                        stylers: [{ color: "#746855" }],
                    },
                    // {
                    //     featureType: "administrative.locality",
                    //     elementType: "labels.text.fill",
                    //     stylers: [{ visibility: "off" }]
                    // },
                    {
                        featureType: "poi",
                        stylers: [{ visibility: "off" }],
                    },
                    // {
                    //     featureType: "poi.park",
                    //     elementType: "geometry",
                    //     stylers: [{ visibility: "off" }]
                    // },
                    {
                        featureType: "poi.park",
                        stylers: [{ visibility: "off" }],
                    },
                    // {
                    //     featureType: "road",
                    //     elementType: "geometry",
                    //     stylers: [{ visibility: "off" }]
                    // },
                    {
                        featureType: "road",
                        stylers: [{ visibility: "off" }],
                    },
                    {
                        featureType: "road",
                        stylers: [{ visibility: "off" }],
                    },
                    {
                        featureType: "road.highway",
                        stylers: [{ visibility: "off" }],
                    },
                    // {
                    //     featureType: "road.highway",
                    //     elementType: "geometry.stroke",
                    //     stylers: [{ visibility: "off" }]
                    // },
                    // {
                    //     featureType: "road.highway",
                    //     elementType: "labels.text.fill",
                    //     stylers: [{ visibility: "off" }]
                    // },
                    {
                        featureType: "transit",
                        stylers: [{ visibility: "off" }],
                    },
                    {
                        featureType: "transit.station",
                        stylers: [{ visibility: "off" }],
                    },
                    {
                        featureType: "water",
                        stylers: [{ visibility: "off" }],
                    },
                    {
                        featureType: "water",
                        stylers: [{ visibility: "off" }],
                    },
                    {
                        featureType: "water",
                        stylers: [{ visibility: "off" }],
                    },
                ];
            } else {
                style = [
                    // {
                    //     featureType: "administrative.locality",
                    //     elementType: "labels.text.fill",
                    //     stylers: [{ visibility: "off" }]
                    // },
                    {
                        featureType: "poi",
                        stylers: [{ visibility: "off" }],
                    },
                    // {
                    //     featureType: "poi.park",
                    //     elementType: "geometry",
                    //     stylers: [{ visibility: "off" }]
                    // },
                    {
                        featureType: "poi.park",
                        stylers: [{ visibility: "off" }],
                    },
                    // {
                    //     featureType: "road",
                    //     elementType: "geometry",
                    //     stylers: [{ visibility: "off" }]
                    // },
                    {
                        featureType: "road",
                        stylers: [{ visibility: "off" }],
                    },
                    {
                        featureType: "road",

                        stylers: [{ visibility: "off" }],
                    },
                    {
                        featureType: "road.highway",
                        stylers: [{ visibility: "off" }],
                    },
                    // {
                    //     featureType: "road.highway",
                    //     elementType: "geometry.stroke",
                    //     stylers: [{ visibility: "off" }]
                    // },
                    // {
                    //     featureType: "road.highway",
                    //     elementType: "labels.text.fill",
                    //     stylers: [{ visibility: "off" }]
                    // },
                    {
                        featureType: "transit",
                        stylers: [{ visibility: "off" }],
                    },
                    {
                        featureType: "transit.station",
                        stylers: [{ visibility: "off" }],
                    },
                    {
                        featureType: "water",
                        stylers: [{ visibility: "off" }],
                    },
                    {
                        featureType: "water",
                        stylers: [{ visibility: "off" }],
                    },
                    {
                        featureType: "water",
                        stylers: [{ visibility: "off" }],
                    },
                ];
                document.getElementById("remove-overlay");
            }
        } 
        // else {
        //     style = [
        //         { elementType: "geometry", stylers: [{ color: "#242f3e" }] },
        //         {
        //             elementType: "labels.text.stroke",
        //             stylers: [{ color: "#242f3e" }],
        //         },
        //         {
        //             elementType: "labels.text.fill",
        //             stylers: [{ color: "#746855" }],
        //         },
        //         {
        //             featureType: "administrative.locality",
        //             elementType: "labels.text.fill",
        //             stylers: [{ color: "#d59563" }],
        //         },
        //         {
        //             featureType: "poi",
        //             elementType: "labels.text.fill",
        //             stylers: [{ color: "#d59563" }],
        //         },
        //         {
        //             featureType: "poi.park",
        //             elementType: "geometry",
        //             stylers: [{ color: "#263c3f" }],
        //         },
        //         {
        //             featureType: "poi.park",
        //             elementType: "labels.text.fill",
        //             stylers: [{ color: "#6b9a76" }],
        //         },
        //         {
        //             featureType: "road",
        //             elementType: "geometry",
        //             stylers: [{ color: "#38414e" }],
        //         },
        //         {
        //             featureType: "road",
        //             elementType: "geometry.stroke",
        //             stylers: [{ color: "#212a37" }],
        //         },
        //         {
        //             featureType: "road",
        //             elementType: "labels.text.fill",
        //             stylers: [{ color: "#9ca5b3" }],
        //         },
        //         {
        //             featureType: "road.highway",
        //             elementType: "geometry",
        //             stylers: [{ color: "#746855" }],
        //         },
        //         {
        //             featureType: "road.highway",
        //             elementType: "geometry.stroke",
        //             stylers: [{ color: "#1f2835" }],
        //         },
        //         {
        //             featureType: "road.highway",
        //             elementType: "labels.text.fill",
        //             stylers: [{ color: "#f3d19c" }],
        //         },
        //         {
        //             featureType: "transit",
        //             elementType: "geometry",
        //             stylers: [{ color: "#2f3948" }],
        //         },
        //         {
        //             featureType: "transit.station",
        //             elementType: "labels.text.fill",
        //             stylers: [{ color: "#d59563" }],
        //         },
        //         {
        //             featureType: "water",
        //             elementType: "geometry",
        //             stylers: [{ color: "#17263c" }],
        //         },
        //         {
        //             featureType: "water",
        //             elementType: "labels.text.fill",
        //             stylers: [{ color: "#515c6d" }],
        //         },
        //         {
        //             featureType: "water",
        //             elementType: "labels.text.stroke",
        //             stylers: [{ color: "#17263c" }],
        //         },
        //     ];
        // }

        let defaultLocation = await getDefaultLocation();

        const map = new google.maps.Map(document.getElementById("map_canvas"), {
            // zoom: 3,
            // styles: style,
            // mapTypeControl: false,
            // streetViewControl: false,
            center: defaultLocation, // {lat:30.7333, lng:76.7794},
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: style
            // mapTypeId: "roadmap",
            // center: result[0][0],
        });

        return map;
    }
}
async function getDefaultLocation(){

    if ($("#agents_map_data").val() != '') {
        allagent = JSON.parse($("#agents_map_data").val());
    }

    if ($("#agentslocations_map_data").val() != '') {
        defaultmaplocation = JSON.parse($("#agentslocations_map_data").val());
        defaultlat = parseFloat(defaultmaplocation[0].lat);
        defaultlong = parseFloat(defaultmaplocation[0].long);
    }

    const defaultLocation = {
        lat: allagent.length !== 0 &&
            allagent[0].agentlog &&
            allagent[0].agentlog['lat'] !== "0.00000000" &&
            allagent[0].agentlog['lat'] !== null ?
            parseFloat(allagent[0].agentlog['lat']) :
            defaultlat,
        lng: allagent.length !== 0 &&
            allagent[0].agentlog &&
            allagent[0].agentlog['long'] !== "0.00000000" &&
            allagent[0].agentlog['long'] !== null ?
            parseFloat(allagent[0].agentlog['long']) :
            defaultlong,
    };

    return defaultLocation;
}