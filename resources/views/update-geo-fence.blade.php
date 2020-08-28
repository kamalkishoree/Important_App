@extends('layouts.vertical', ['title' => 'Geo Fence'])

@section('css')
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/mohithg-switchery/mohithg-switchery.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/multiselect/multiselect.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/selectize/selectize.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}" rel="stylesheet"
    type="text/css" />


<!-- for File Upload -->

<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<style>
    #map-canvas {
  height: 90%;
  margin: 0px;
  padding: 0px;
  position: unset;
}
</style>
@endsection

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-6">
            <div class="page-title-box">
                <h4 class="page-title">Settings</h4>
            </div>
        </div>
        <div class="col-6">
            <div class="page-title-box">
                <a href="{{route('geo.fence.list')}}"><h4 class="page-title">View All</h4></a>
            </div>
        </div>
    </div>

    <form id="" method="post" action="{{route('geo-fence.update', $geo->id)}}">
    @method('PUT')
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
                            <input type="text" name="name" id="name" value="{{ old('name', $geo->name ?? '')}}" placeholder="ABC Deliveries" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="Description">Description (Optional)</label>
                            <textarea class="form-control" id="Description" name="description" >{{ old('description', $geo->description ?? '')}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label>Team</label> <br />
                            <select id="selectize-select" name="team_id">
                                <option data-display="Select">No Team Selected</option>
                                @foreach($teams as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkmeout0">
                                <label class="custom-control-label" for="checkmeout0">All {{ auth()->user()->getPreference->agent_name ?? 'Agents' }}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group mb-3 agent-selection">
                            <label>{{ auth()->user()->getPreference->agent_name ?? 'Agents' }}</label>
                            <select class="form-control select2-multiple" data-toggle="select2" multiple="multiple"
                                    data-placeholder="Choose ..." name="agents[]" id="agents">
                                @foreach($agents as $agent)
                                <option value="{{$agent->id}}" data-team-id={{ $agent->team_id }} 
                                @if(in_array($agent->id,$agents->pluck('id')->toArray())) selected @endif
                                >{{$agent->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <button type="button"
                            class="btn btn-block btn-outline-primary waves-effect waves-light">Cancel</button>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-block btn-primary waves-effect waves-light">Save</button>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-7">
            <div class="card-box" style="height:500px;">
                <!-- <div id="gmaps-basic" class="gmaps"></div> -->
                <div id="map-canvas"></div>
            </div>
        </div>
    </div>
    </form>
</div>
@endsection

@section('script')
<!-- google maps api -->
<script src="https://maps.google.com/maps/api/js?key=AIzaSyB85kLYYOmuAhBUPd7odVmL6gnQsSGWU-4&v=3.exp&libraries=drawing"></script>

<!-- Plugins js-->
<script src="{{asset('assets/libs/selectize/selectize.min.js')}}"></script>
<script src="{{asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js')}}"></script>
<script src="{{asset('assets/libs/multiselect/multiselect.min.js')}}"></script>
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{asset('assets/libs/devbridge-autocomplete/devbridge-autocomplete.min.js')}}"></script>
<script src="{{asset('assets/libs/jquery-mockjax/jquery-mockjax.min.js')}}"></script>

<!-- Plugins js-->
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-advanced.init.js')}}"></script>

<script>
    var map; // Global declaration of the map
    function initialize() {      
      var zoomLevel = '{{$geo->zoom_level}}';
      var coordinate='{{$geo->geo_array}}';
      coordinate = coordinate.split('(');
      coordinate = coordinate.join('[');
      coordinate = coordinate.split(')');
      coordinate = coordinate.join(']');
      coordinate = "["+coordinate;
      coordinate = coordinate+"]";      
      coordinate = JSON.parse(coordinate);      

      var triangleCoords=[];
      const lat1 = coordinate[0][0];
      const long1 = coordinate[0][1];

      var max_x=lat1;
      var min_x=lat1;
      var max_y=long1;
      var min_y=long1;

      $.each( coordinate, function( key, value ) {        
      
        if(value[0]>max_x){
          max_x=value[0];
        }
        if(value[0]<min_x){
          min_x=value[0];
        }
        if(value[1]>max_y){
          max_y=value[1];
        }
        if(value[1]<min_y){
          min_y=value[1];
        }

        triangleCoords.push(new google.maps.LatLng(value[0], value[1]));
      });

      var myLatlng = new google.maps.LatLng((min_x + ((max_x - min_x) / 2)), (min_y + ((max_y - min_y) / 2)));
      var myOptions = {
        zoom: parseInt(zoomLevel),
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      }
      map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);      
      myPolygon = new google.maps.Polygon({
        paths: triangleCoords,
        //draggable: true, // turn off if it gets annoying
        //editable: true,
        strokeColor: '#bb3733',
        //strokeOpacity: 0.8,
        //strokeWeight: 2,
        fillColor: '#bb3733',
        //fillOpacity: 0.35
      });
      myPolygon.setMap(map);
      
    }
    google.maps.event.addDomListener(window, 'load', initialize);



    // onteam change change the selected agents in the list //

    $(function(){
        $('#checkmeout0').change(function(){
            if(this.checked){
                $('.agent-selection select option').each(function () {
                    $(this).attr('selected', true);
                });
            }else{
                $('.agent-selection select option').each(function () {
                    $(this).attr('selected', false);
                });
            }
            $('#agents').trigger('change');
        });
    });

    $(function(){
        $('#selectize-select').change(function(){
            var team_id = $(this).children("option:selected").val();
            var team_array = [];
            team_array.push(team_id);

            $('.agent-selection select option').each(function () {
                $(this).attr('selected', false);
            });
            $('.agent-selection select option').each(function () {
                if($(this).attr('data-team-id') == team_array[0]){
                    $(this).attr('selected', true);
                }
            },team_array);
            $('#agents').trigger('change');
        });
    });

    

</script>
@endsection