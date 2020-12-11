@extends('layouts.vertical', ['title' => 'Customers'])

@section('css')
<link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />


<!-- for File Upload -->

<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/css/intlTelInput.css'>
<style>
// workaround
.intl-tel-input {
  display: table-cell;
}

.inner-div {
        width: 50%;
        float: left;
    }
.intl-tel-input .selected-flag {
  z-index: 4;
}
.intl-tel-input .country-list {
  z-index: 5;
}
.input-group .intl-tel-input .form-control {
  border-top-left-radius: 4px;
  border-top-right-radius: 0;
  border-bottom-left-radius: 4px;
  border-bottom-right-radius: 0;
}

.modal.fadeIn {
  opacity:.4;
}
.pac-container, .pac-container .pac-item { z-index: 99999 !important; }
</style>
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title page-title1">Customers</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <div class="text-sm-left">
                                @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('success') !!}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> Add Customer</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100"  id="pricing-datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone number</th>
                                    <th>Status</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                <tr>
                                    <td>
                                        {{$customer->name}}
                                    </td>
                                    <td>
                                        {{$customer->email}}
                                    </td>
                                    <td>
                                        {{$customer->phone_number}}
                                    </td>
                                    <td>
                                    <div class="custom-control custom-switch">
                                        <input data-id="{{$customer->id}}" type="checkbox" class="custom-control-input" id="customSwitch1" name="is_default" value="y" {{ $customer->status == 'Active' ? 'checked' : ''}} >
                                        <label class="custom-control-label" for="customSwitch1"></label>
                                    </div>
                                    </td>

                                    <td>
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div"> <a href="#" userId="{{$customer->id}}" class="action-icon editIcon"> <i class="mdi mdi-square-edit-outline"></i></a></div>
                                            <div class="inner-div">
                                                <form method="POST" action="{{route('customer.destroy', $customer->id)}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary-outline action-icon"> <i class="mdi mdi-delete"></i></button>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row address" id="add0" style="display: none;">
                    <input type="text" id="add0-input" name="test" class="autocomplete form-control add0-input" placeholder="Address">
                </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>

</div>
@include('Customer.customer-modal')

@endsection

@section('script')

<!-- Plugins js-->

<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-advanced.init.js')}}"></script>
<script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>

<script src="{{asset('assets/js/storeAgent.js')}}"></script>

<!-- for File Upload -->
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>
<script src="{{asset('assets/libs/datatables/datatables.min.js')}}"></script>



<script>

    var autocomplete = {};
    var autocompletesWraps = ['add0'];
    var count = 1; editCount = 0;
    $('.openModal').click(function(){
        $('#add-customer-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
        autocompletesWraps.push('add1');
        loadMap(autocompletesWraps);
    });

    $(document).on('click', '.showMap', function(){
        var no = $(this).attr('num');
        var lats = document.getElementById(no+'-latitude').value;
        var lngs = document.getElementById(no+'-longitude').value;

        document.getElementById('map_for').value = no;

        if(lats == null || lats == '0'){
            lats = 51.508742;
        }
        if(lngs == null || lngs == '0'){
            lngs = -0.120850;
        }

        var myLatlng = new google.maps.LatLng(lats, lngs);
            var mapProp = {
                center:myLatlng,
                zoom:5,
                mapTypeId:google.maps.MapTypeId.ROADMAP
              
            };
            var map=new google.maps.Map(document.getElementById("googleMap"), mapProp);
                var marker = new google.maps.Marker({
                  position: myLatlng,
                  map: map,
                  title: 'Hello World!',
                  draggable:true  
              });
            document.getElementById('lat_map').value= lats;
            document.getElementById('lng_map').value= lngs ; 
            // marker drag event
            google.maps.event.addListener(marker,'drag',function(event) {
                document.getElementById('lat_map').value = event.latLng.lat();
                document.getElementById('lng_map').value = event.latLng.lng();
            });

            //marker drag event end
            google.maps.event.addListener(marker,'dragend',function(event) {
                var zx =JSON.stringify(event);
                console.log(zx);


                document.getElementById('lat_map').value = event.latLng.lat();
                document.getElementById('lng_map').value = event.latLng.lng();
                //alert("lat=>"+event.latLng.lat());
                //alert("long=>"+event.latLng.lng());
            });
            $('#add-customer-modal').addClass('fadeIn');
        $('#show-map-modal').modal({
            backdrop: 'static',
            keyboard: false
        });

    });

    $('#show-map-modal').on('hide.bs.modal', function () {
         $('#add-customer-modal').removeClass('fadeIn');

    });

    $(document).on('click', '.selectMapLocation', function () {

        var mapLat = document.getElementById('lat_map').value;
        var mapLlng = document.getElementById('lng_map').value;
        var mapFor = document.getElementById('map_for').value;
        console.log(mapLat+'-'+mapLlng+'-'+mapFor);
        document.getElementById(mapFor + '-latitude').value = mapLat;
        document.getElementById(mapFor + '-longitude').value = mapLlng;


        $('#show-map-modal').modal('hide');
    });

    $(document).ready( function () {
        $('#pricing-datatable').DataTable();
        loadMap(autocompletesWraps);
        
    });

    $(function() {
        $('.custom-control-input').change(function() {
            var status = $(this).prop('checked') == true ? "Active" : 'In-Active'; 
            var user_id = $(this).data('id'); 
             
            $.ajax({
                type: "GET",
                dataType: "json",
                url: '/changeStatus',
                data: {'status': status, 'id': user_id},
                success: function(data){
                  console.log(data.success)
                }
            });
        })
    });

    $(document).on('click', '.addField', function(){
        count = count + 1;

        $(document).find('#address-map-container').before('<div class="row address" id="add'+count+'"><div class="col-md-4"><div class="form-group" id=""><input type="text"  class="form-control" placeholder="Short Name" name="short_name[]"></div></div><div class="col-md-5"><div class="form-group input-group" id=""><input type="text" id="add'+count+'-input" name="address[]" class="autocomplete form-control" placeholder="Address"><div class="input-group-append"><button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="add'+count+'"> <i class="mdi mdi-map-marker-radius"></i></button></div><input type="hidden" name="latitude[]" id="add'+count+'-latitude" value="0" /><input type="hidden" name="longitude[]" id="add'+count+'-longitude" value="0" /></div></div><div class="col-md-3"><div class="form-group" id=""><input type="text"  class="form-control" placeholder="Post Code" name="post_code[]"></div></div></div>');

        autocompletesWraps.indexOf('add'+count) === -1 ? autocompletesWraps.push('add'+count) : console.log("This item already exists");
        
        //console.log(autocompletesWraps);
        loadMap(autocompletesWraps);

    });

    var latitudes = []; 
    var longitude = [];

    function loadMap(autocompletesWraps){

        
        $.each(autocompletesWraps, function(index, name) {
            const geocoder = new google.maps.Geocoder;
        
            if($('#'+name).length == 0) {
                return;
            }
            //autocomplete[name] = new google.maps.places.Autocomplete(('.form-control')[0], { types: ['geocode'] }); console.log('hello');
            autocomplete[name] = new google.maps.places.Autocomplete(document.getElementById(name+'-input'), { types: ['geocode'] });
                
            google.maps.event.addListener(autocomplete[name], 'place_changed', function() {
                
                var place = autocomplete[name].getPlace();

                geocoder.geocode({'placeId': place.place_id}, function (results, status) {
                    
                    if (status === google.maps.GeocoderStatus.OK) {
                        const lat = results[0].geometry.location.lat();
                        const lng = results[0].geometry.location.lng();
                        console.log(latitudes);
                        document.getElementById(name + '-latitude').value = lat;
                        document.getElementById(name + '-longitude').value = lng;
                    }
                });
            });
        });

    }

</script>

<script>
    $(".editIcon").click(function (e) {  

        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
       
        var uid = $(this).attr('userId');

        $.ajax({
            type: "get",
            url: "<?php echo url('customer'); ?>" + '/' + uid + '/edit',
            data: '',
            dataType: 'json',
            success: function (data) {

                $('.page-title1').html('Hello');
                console.log('data');

                $('#edit-customer-modal #editCardBox').html(data.html);
                $('#edit-customer-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                editCount = data.addFieldsCount;
                for (var i = 1; i <= data.addFieldsCount; i++) {
                    autocompletesWraps.push('edit'+i);
                    loadMap(autocompletesWraps);
                }

            },
            error: function (data) {
                console.log('data2');
            }
        });
    });

    $(document).on('click', '.editInput', function(){
        editCount = editCount + 1;

        $(document).find('#editAddress-map-container').before('<div class="row address" id="edit'+editCount+'"><div class="col-md-4"><div class="form-group" id=""><input type="text"  class="form-control" placeholder="Short Name" name="short_name[]"></div></div><div class="col-md-5"><div class="form-group input-group" id=""><input type="text" id="edit'+editCount+'-input" name="address[]" class="autocomplete form-control" placeholder="Address"><div class="input-group-append"><button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="edit'+editCount+'"> <i class="mdi mdi-map-marker-radius"></i></button></div><input type="hidden" name="latitude[]" id="edit'+editCount+'-latitude" value="0" /><input type="hidden" name="longitude[]" id="edit'+editCount+'-longitude" value="0" /></div></div><div class="col-md-3"><div class="form-group" id=""><input type="text"  class="form-control" placeholder="Post Code" name="post_code[]"></div></div></div>');

        autocompletesWraps.indexOf('edit'+editCount) === -1 ? autocompletesWraps.push('edit'+editCount) : console.log("This item already exists");
        
        //console.log(autocompletesWraps);
        loadMap(autocompletesWraps);

    });

    $(document).on('click', '.submitEditForm', function(){ 
        document.getElementById("edit_customer").submit();
    });

    
</script>

@endsection