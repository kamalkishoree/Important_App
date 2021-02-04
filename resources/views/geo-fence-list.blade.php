@extends('layouts.vertical', ['title' => 'Geo'])

@section('css')
<link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />


<!-- for File Upload -->

<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<style>
    #map-canvas {
  height:100%;
  margin: 0px;
  padding: 0px;
  position: unset;
}
</style>
@endsection

@section('content')
@include('modals.add-agent')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Geo-fences</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="text-sm-left">
                @if (\Session::has('success'))
                <div class="alert alert-success">
                    <span>{!! \Session::get('success') !!}</span>
                </div>
                @endif
            </div>
        </div>
    </div>


    <!-- end page title -->
    <div class="row">
        <div class="col-12 col-md-3">
            <div class="card">
                <div class="card-body">

                    <div class="row mb-2">
                        
                        <div class="col-sm-12 text-right">
                            <a href="{{ route('geo-fence.index') }}"><button type="button" class="btn btn-blue waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>Add Geo-fence</button></a>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($geos as $geo)
                                <tr>
                                    <td class="table-user">
                                        <a href="javascript:void(0);"
                                            class="text-body font-weight-semibold">{{$geo->name}}</a>
                                    </td>

                                    <td>
                                        <a href="{{route('geo-fence.edit', $geo->id)}}" class="action-icon"> <i
                                                class="mdi mdi-square-edit-outline"></i></a>
                                        <!-- <a href="{{route('geo-fence.destroy', $geo->id)}}" class="action-icon">
                                            <i class="mdi mdi-delete"></i>
                                        </a> -->
                                        <form method="POST" action="{{route('geo-fence.destroy', $geo->id)}}" class="action-icon">
                                            @csrf
                                            @method('DELETE')
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary-outline action-icon"> <i
                                                        class="mdi mdi-delete"></i></button>

                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->


        <div class="col-12 col-md-9">
            <div class="card-box p-0 m-0" style="height:650px;">
                <div id="map-canvas"></div>
            </div>
        </div>

    </div>

    @php
    $key = session('preferences.map_key_1') != null ? session('preferences.map_key_1'):'kdsjhfkjsdhfsf';
    @endphp
</div>
@endsection

@section('script')
<!-- google maps api -->
<script src="https://maps.google.com/maps/api/js?key={{$key}}&v=3.exp&libraries=drawing"></script>

<script>
    var no_parking_geofences_json = {!! json_encode($all_coordinates) !!};

    // function gm_authFailure() {
                
    //             $('.excetion_keys').append('<span><i class="mdi mdi-block-helper mr-2"></i> <strong>Google Map</strong> key is not valid</span><br/>');
    //             $('.displaySettingsError').show();
    // };
    var map; // Global declaration of the map
    function initialize() {     

      var myLatlng = new google.maps.LatLng({{ $center['lat'] }},{{ $center['lng']  }});
      console.log(myLatlng);
      var myOptions = {
        zoom: parseInt(13),
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      }
      map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);     


      for (var i = 0, length = no_parking_geofences_json.length; i < length; i++) {
      data = no_parking_geofences_json[i];
      var infowindow = new google.maps.InfoWindow();
      var no_parking_geofences_json_geo_area = new google.maps.Polygon({
        paths: data.coordinates,
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#ff0000',
        fillOpacity: 0.35,
        geo_name: data.name,
        geo_pos: data.coordinates[0],
        
      });

      no_parking_geofences_json_geo_area.setMap(map);

    }


    }


    google.maps.event.addDomListener(window, 'load', initialize);


</script>

@endsection