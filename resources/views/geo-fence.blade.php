@php
$check = 0;
if(isset($newchange) ){
dd($newchange);
$check = $newchnage;
}
@endphp
@extends('layouts.vertical', ['title' => 'Geo Fence'])


@section('css')
    <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/mohithg-switchery/mohithg-switchery.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/multiselect/multiselect.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/selectize/selectize.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />


    <!-- for File Upload -->

    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        #map-canvas {
            height: 90%;
            margin: 0px;
            padding: 0px;
            position: unset;
        }

        .serch {
            width: 100%;
            margin: 0px;
        }

        .imageagent {
            border-radius: 50%;
            height: 40px;
            width: 40px;
            margin-right: 15px;
        }

        .cornar {
            border: solid thin;
            padding-top: 10px;
            height: 240px;
            width: 103%;
            overflow-y: auto
        }

        .teamshow {
            margin-left: 58px;

        }

        .display {
            height: 35px;
            width: 67px;
        }

        .boxes {
            margin-bottom: 10px;
        }

        .new {
            vertical-align: initial !important;
            display: revert !important;
        }

        .agentcheck {}

    </style>
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-6">
                <div class="page-title-box">
                    <h4 class="page-title"> <a href="{{ route('geo.fence.list') }}">
                            <h4 class="page-title">Back</h4>
                        </a></h4>
                </div>
            </div>
            <div class="col-6">
                <div class="page-title-box">

                </div>
            </div>
        </div>

        <form id="geo_form" action="{{ route('geo-fence.store') }}" method="POST">
            @csrf
            <input type="hidden" name="latlongs" value="" id="latlongs" />
            <input type="hidden" name="zoom_level" value="13" id="zoom_level" />
            <div class="row">
                <div class="col-lg-5">
                    <div class="card-box">
                        <h4 class="header-title mb-3">Add Geo Fence</h4>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" placeholder="ABC Deliveries"
                                        class="form-control">
                                    @if ($errors->has('name'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="Description">Description (Optional)</label>
                                    <textarea class="form-control" id="Description" name="description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label>Team</label> <br />
                                    <select id="selectize-select" name="team_id">
                                        <option value="0">All</option>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input all" id="checkmeout0">
                                                <label class="custom-control-label" for="checkmeout0">Select All
                                                    {{ Session::get('agent_name') ? Session::get('agent_name') : 'Agent' }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="search" placeholder="Search" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2 cornar">

                            @foreach ($agents as $agent)
                        <div class="col-md-6 boxes card-box agent_boxes team_{{ $agent->team_id ?? 0 }} agent_{{$agent->id}}" >
                                    <div class="custom-control custom-checkbox">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="checkbox" class="custom-control-input agent_checkbox team_checkbox_{{ $agent->team_id ?? 0 }}" id="{{ $agent->id ?? 0 }}" name="agents[]">
                                                <label class="custom-control-label new" for="{{ $agent->id }}"></label>
                                                <img class="imageagent"
                                                    src="{{ Phumbor::url('' . URL::to('/agents') . '/' . $agent->profile_picture . '')->trim() }}"
                                                    alt="" style="border-radius:50%; ">
                                            </div>
                                            <div class="col-md-8">
                                                <span>{{ $agent->name }}</span><br>
                                                <span>{{ isset($agent->team->name) ? $agent->team->name : 'No Team Alloted' }}</span>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <button type="button"
                                    class="btn btn-block btn-outline-blue waves-effect waves-light">Cancel</button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-block btn-blue waves-effect waves-light">Save</button>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card-box" style="height:96%;">
                        <input id="pac-input" class="controls serch" type="text" placeholder="Search Location" />
                        <div id="map-canvas"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <!-- google maps api -->
    <script
        src="https://maps.google.com/maps/api/js?key=AIzaSyB85kLYYOmuAhBUPd7odVmL6gnQsSGWU-4&v=3.exp&libraries=drawing,places">
    </script>

    <!-- Plugins js-->
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script src="{{ asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/multiselect/multiselect.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/libs/devbridge-autocomplete/devbridge-autocomplete.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/libs/jquery-mockjax/jquery-mockjax.min.js') }}">
    </script> --}}
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Plugins js-->
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <!-- Page js-->
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/jquery.cookie.js') }}"></script>

    <script>
        var map; // Global declaration of the map
        var iw = new google.maps.InfoWindow(); // Global declaration of the infowindow
        var lat_longs = new Array();
        var markers = new Array();
        var drawingManager;
        var newlocation = '<?php echo json_encode($coninates); ?>';
        var first_location = JSON.parse(newlocation);
        var lat = parseFloat(first_location.lat);
        var lng = parseFloat(first_location.lng);
        // console.log(first_location);
        function initialize() {

            var myLatlng = new google.maps.LatLng(lat, lng);
            var myOptions = {
                zoom: 13,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            // Bias the SearchBox results towards current map's viewport.

            map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);


            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [google.maps.drawing.OverlayType.POLYGON]
                },
                polygonOptions: {
                    //editable: true,
                    //draggable: true
                    strokeColor: '#bb3733',
                    fillColor: '#bb3733',
                }
            });
            drawingManager.setMap(map);

            google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
                var newShape = event.overlay;
                newShape.type = event.type;

            });

            google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
                overlayClickListener(event.overlay);
                var vertices_val = $('#latlongs').val();
                //var vertices_val = event.overlay.getPath().getArray();
                console.log(vertices_val);
                if (vertices_val == null || vertices_val === '') {
                    $('#latlongs').val(event.overlay.getPath().getArray());
                    console.log(map.getZoom());
                    $('#zoom_level').val(map.getZoom());
                } else {
                    alert('You can draw only one zone at a time');
                    event.overlay.setMap(null);
                }
            });

            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    const icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25),
                    };
                    // Create a marker for each place.
                    markers.push(
                        new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                        })
                    );

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }

        function overlayClickListener(overlay) {
            google.maps.event.addListener(overlay, "mouseup", function(event) {
                $('#latlongs').val(overlay.getPath().getArray());
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);

        function addLine() {
            drawingManager.setMap(map);
        }

        function removeLine() {
            //drawingManager.setMap(null);
        }

        $(function() {
            $('#save').click(function() {
                //iterate polygon latlongs?
            });
        });


        // onteam change change the selected agents in the list //

        // $(function() {
        //     $('#checkmeout0').change(function() {
        //         if (this.checked) {
        //             $('.agent-selection select option').each(function() {
        //                 $(this).attr('selected', true);
        //             });
        //         } else {
        //             $('.agent-selection select option').each(function() {
        //                 $(this).attr('selected', false);
        //             });
        //         }
        //         $('#agents').trigger('change');
        //     });
        // });

        $(function() {
            $('#selectize-select').change(function() {
                var team_id = $(this).children("option:selected").val();
                var team_array = [];
                team_array.push(team_id);

                $('.agent-selection select option').each(function() {
                    $(this).attr('selected', false);
                });
                $('.agent-selection select option').each(function() {
                    if ($(this).attr('data-team-id') == team_array[0]) {
                        $(this).attr('selected', true);
                    }
                }, team_array);
                $('#agents').trigger('change');
            });
        });

        $("#geo_form").on("submit", function(e) {
            var lat = $('#latlongs').val();
            var trainindIdArray = lat.replace("[", "").replace("]", "").split(',');
            var length = trainindIdArray.length;

            if (length < 6) {
                Swal.fire(
                    'Select Location?',
                    'Please Drow a Location On Map first',
                    'question'
                )
                e.preventDefault();
            }


        })

        $(".all").click(function() {
            if ($(this).is(':checked')) {
                var select = $("#selectize-select option:selected").val();
                if(select == 0){
                    $('.agent_checkbox').attr('checked', true);
                    $(".agent_boxes").addClass("selected");
                }
                $('.team_checkbox_'+select).attr('checked', true);
                $(".team_"+select).addClass("selected");
            } else {
                $('.agent_checkbox').attr('checked', false);
                    $(".agent_boxes").removeClass("selected");            }
        });

        // $('select').on('change', function(e) {
        //     var select = $("#selectize-select option:selected").val();
        // });

        $('select').on('change', function(e) {
            var select = $("#selectize-select option:selected").val();
            $('#checkmeout0').prop('checked', false);
            
            if(select == 0){
                $('.agent_boxes').show();
            }else{
                $('.agent_boxes').hide();
                $('.selected').show(); 
                $('.team_' + select).show(); 
            }
             

        });

        $(".agent_checkbox").click(function(){
        var id = $(this).attr('id');
        var isChecked = $(this).prop('checked');
        console.log(id);
        console.log(isChecked);
        if(isChecked)
            $(".agent_"+id).addClass("selected");
        else
        $(".agent_"+id).removeClass("selected");
    });

    </script>
@endsection
