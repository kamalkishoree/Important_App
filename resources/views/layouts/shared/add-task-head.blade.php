<style>
        #adds {
            margin-bottom: 14px;
        }

        .shows {
            display: none;
        }

        .rec {
            margin-bottom: 7px;
        }

        .needsclick {

            margin-left: 27%;
        }

        .padd {
            padding-left: 9% !important;
        }

        .newchnage {
            margin-left: 27% !important;
        }

        .address {
            margin-bottom: 6px
        }

        .tags {
            
        }

        #typeInputss {
            overflow-y: auto;
            overflow-x: hidden;
            height: 130px;
            /* display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column; */
        }

        .upload {
            margin-bottom: 20px;
            margin-top: 10px;

        }

        .span1 {
            color: #ff0000;
        }

        .check {
            
        }

        .newcheck {
            margin-left: -54px;
        }

        .upside {
            margin-top: -10px;
        }

        .newgap {
            margin-top: 11px !important;
        }

        

        .append {
            margin-bottom: 15px;
        }

        .spanbold {
            font-weight: bolder;
        }

        .copyin {
            background-color: rgb(148 148 148 / 11%);
            margin-top: 10px;
            
 
        }
        .copyin1 {
            background-color: rgb(148 148 148 / 11%);
           
        }
        hr.new3 {
         border-top: 1px dashed white;
         margin: 0 0 .5rem 0;
       }
       #spancheck{
           display: none;
       }
       .imagepri{
        min-width: 50px;
           height: 50px;
           width: 50px;
           border-style: groove;
           margin-left: 5px;
           margin-top: 5px;
       }
       .withradio{
       
        
       }
       .showsimage{
        margin-top: 31px;
       }
       .showshadding{
        margin-left: 98px;
       }
       .newchnageimage{
       }
       .showsimagegall{
        margin-top: 20px;

       }
       .allset{
           margin-left: 9px !important;
           margin-right: 9px !important;
           padding-top: 10px;
       }
       .hidealloction{
           display: none;
       }
       .ui-menu.ui-autocomplete { z-index: 9000 !important; }

  .pac-container, .pac-container .pac-item { z-index: 9999 !important; }
</style>
<div id="task-modal-header" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="">
            <div class="modal-header align-items-center border-0 mb-md-0 mb-3">
                <h4 class="page-title m-0">Route Pricing Popup</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="taskFormHeader" method="post" enctype="multipart/form-data" action="{{ route('tasks.store') }}">
                @csrf
                <div class="modal-body p-14 pt-0" id="addCardBox">
                    
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-blue waves-effect waves-light submitTaskHeader">Submit</button>
                </div>
            </form>   
        </div>
    </div>
</div>

<div id="show-map-Header" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">

            <div class="modal-header border-0">
                <h4 class="modal-title">Select Location</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body px-3 py-0">
                
                <div class="row">
                    <form id="task_form_header" action="#" method="POST" style="width: 100%">
                        <div class="col-md-12">
                            <div id="googleMapHeader" style="height: 500px; min-width: 500px; width:100%"></div>
                            <input type="hidden" name="lat_input" id="lat_map_header" value="0" />
                            <input type="hidden" name="lng_input" id="lng_map_header" value="0" />
                            <input type="hidden" name="for" id="map_for_header" value="" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-blue waves-effect waves-light selectMapOnHeader">Ok</button>
                <!--<button type="Cancel" class="btn btn-blue waves-effect waves-light cancelMapLocation">cancel</button>-->
            </div>
        </div>
    </div>
</div>
            @php 
                $style = "";
                if(session('preferences.twilio_status') != 'invalid_key'){
                        $style = "display:none;";
                }
            @endphp
            {{-- <div class="row displaySettingsError" style="{{$style}}">
                <div class="col-12">
                    <div class="alert alert-danger excetion_keys" role="alert">
                        @if(session('preferences.twilio_status') == 'invalid_key')
                        <span><i class="mdi mdi-block-helper mr-2"></i> <strong>Twilio</strong> key is not valid</span> <br/>
                        @endif
                    </div>
                </div>


            </div> --}}

<div class="row address" id="addHeader0" style="display: none;">
    <input type="text" id="addHeader0-input" name="address" class="autocomplete form-control addHeader0-input" placeholder="Address">
    <input type="hidden" name="latitude[]" id="addHeader0-latitude" value="0" class="cust_latitude" />
    <input type="hidden" name="longitude[]" id="addHeader0-longitude" value="0" class="cust_longitude" />
</div>
@php
    $key    = session('preferences.map_key_1') != null ? session('preferences.map_key_1'):'kdsjhfkjsdhfsf';
    $theme  = \App\Model\ClientPreference::where(['id' => 1])->first('theme');


@endphp
<link href="{{ asset('assets/libs/selectize/selectize.min.css') }}" rel="stylesheet" type="text/css" />

<script src="{{ asset('assets/js/jquery-ui.min.js') }}" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
{{-- <script src='https://cdn.rawgit.com/pguso/jquery-plugin-circliful/master/js/jquery.circliful.min.js'></script> --}}
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB85kLYYOmuAhBUPd7odVmL6gnQsSGWU-4&libraries=places"></script>  --}}
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script defer
        src="https://maps.googleapis.com/maps/api/js?key={{$key}}&libraries=places,drawing,visualization&v=weekly">
        </script>
<script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
<script src="{{ asset('assets/libs/multiselect/multiselect.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/clockpicker/clockpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

<!-- Page js-->
<script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>
<script>
    var theme      = {!!json_encode($theme)!!};
    
    
    var maoArray = {};
    var autoWrap = ['addHeader0'];
    var count = 1; editCount = 0; var a = 0; countZ = 1;
   /* $('.openModal').click(function(){
        $('#task-modal-header').modal({
            //backdrop: 'static',
            keyboard: false
        });
        autoWrap.push('addHeader1');
        loadMap(autoWrap);
    });*/

    $(document).ready(function(){
      loadMapHeader(autoWrap);
      
    });
    function gm_authFailure() {
                
                $('.excetion_keys').append('<span><i class="mdi mdi-block-helper mr-2"></i> <strong>Google Map</strong> key is not valid</span><br/>');
                $('.displaySettingsError').show();
    };
    function runPicker(){
        $('.datetime-datepicker').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",

            //wrap:true,
        });

        $('.selectpicker').selectpicker();
    }

    var latitudes = []; 
    var longitude = [];

    $(".addTaskModalHeader").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        autoWrap.indexOf('addHeader1') === -1 ? autoWrap.push('addHeader1') : '' ; 
        // console.log("exists");
        e.preventDefault();
       
        $.ajax({
            type: "get",
            url: "<?php echo url('tasks'); ?>" + '/create',
            data: '',
            dataType: 'json',
            success: function (data) {

                //$('.page-title1').html('Hello');
                //console.log('data');

                $('#task-modal-header #addCardBox').html(data.html);

                $('#task-modal-header').find('.selectizeInput').selectize();

                //$('#task-modal-header #selectize-optgroups').selectize();
                //$('#task-modal-header #selectize-optgroups').selectize();

                $(".shows").hide();
                $(".addspan").hide();
                $(".tagspan").hide();
                $(".tagspan2").hide();
                $(".searchspan").hide();
                $(".appoint").hide();
                $(".datenow").hide();
                $("#AddressInput a").click(function() {
                    $(".shows").show();
                    $(".append").hide();
                    $(".searchshow").hide();
                    $('input[name=ids').val('');
                    $('input[name=search').val('');
                    $('.copyin').remove();
                    autoWrap = ['addHeader0', 'addHeader1'];
                    countZ = 1;
                });
                $("#Inputsearch a").click(function() {
                    $(".shows").hide();
                    $(".append").hide();
                    $(".searchshow").show();
                    $('.copyin').remove();
                    autoWrap = ['addHeader0', 'addHeader1'];
                    countZ = 1;
                });

                $("#nameInputHeader").keyup(function() {
                    $(".shows").hide();
                    $(".oldhide").show();
                    $(".append").hide();
                    $('input[name=ids').val('');
                    $('.copyin').remove();
                    autoWrap = ['addHeader0', 'addHeader1'];
                    countZ = 1;
                });

                $("#file").click(function() {
                    $('.showsimagegall').hide();
                    $('.imagepri').remove();
                    
                });

                loadMapHeader(autoWrap);
                searchRes();
                $('#task-modal-header').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                runPicker();
            },
            error: function (data) {
            }
        });
    });

    var CSRF_TOKEN = $("input[name=_token]").val();

    function searchRes(){

        $("#task-modal-header #searchCust").autocomplete({
            source: function(request, response) {
                // Fetch data
                $.ajax({
                    url: "{{route('search')}}",
                    type: 'post',
                    dataType: "json",
                    data: {
                        _token: CSRF_TOKEN,
                        search: request.term
                    },
                    success: function(data) {
                        response(data);
                        //console.log(data);
                    }
                });
            },
            select: function(event, ui) {
                // Set selection
                $('#task-modal-header #searchCust').val(ui.item.label); // display the selected text
                $('#task-modal-header #cusid').val(ui.item.value); // save selected id to input
                add_event(ui.item.value);
                $(".oldhide").hide();
                return false;
            }
        });
    }

    function add_event(ids) {

          $.ajax({
              url: "{{ route('search') }}",
              type: 'post',
              dataType: "json",
              data: {
                  _token: CSRF_TOKEN,
                  id: ids
              },
              success: function(data) {
                  var array = data;
                  
                  jQuery.each(array, function(i, val) {
                      $(".withradio").append(
                          '<div class="append"><div class="custom-control custom-radio count"><input type="radio" id="' +
                          val.id + '" name="old_address_id" value="' + val
                          .id +
                          '" class="custom-control-input redio callradio"><label class="custom-control-label" for="' +
                          val.id + '"><span class="spanbold">' + val.short_name +
                          '</span>-' + val.address +
                          '</label></div></div>');
                  });

              }
          });
      }
      var post_count = 2;
    $(document).on('click', '.subTaskHeader', function(){
      var cur = countZ;
      countZ = countZ + 1;
      //console.log(countZ);
        var $clone = $('.cloningDiv').clone();
        $clone.removeClass('cloningDiv');
        $clone.removeClass('copyin1');
        $clone.addClass('copyin');
        $clone.addClass('repeated-block');       
        
        $clone.find('.cust_add_div').prop('id', 'addHeader' + countZ);
        $clone.find('.cust_add').prop('id', 'addHeader' + countZ +'-input');
        $clone.find('.cust_btn').prop('id', 'addHeader' + countZ);
        $clone.find('.cust_btn').prop('num', 'addHeader' + countZ);
        $clone.find('.cust_latitude').prop('id', 'addHeader' + countZ +'-latitude');
        $clone.find('.cust_longitude').prop('id', 'addHeader' + countZ +'-longitude');

        var inputid = $clone.find('.redio');
        var rand =  Math.random().toString(36).substring(7);
        var count1 = 1;
        $.each(inputid, function(index, elem){
               
            var jElem = $(elem); // jQuery element
            //var name = jElem.prop('id');
            
            ////console.log(name + "-hello");
            //name = rand;
            
            //rand += count1;
            ////console.log(name);
            jElem.prop('id', rand+count1);
            jElem.prop('name', 'old_address_id' + cur);
            jElem.prop('checked', false);
            count1++;
        });
        var count2 = 1;
        var labels = $clone.find('label');
        $.each(labels, function(index, elem){
               
            var jElem = $(elem); // jQuery element
            //var name = jElem.prop('for');
            
            ////console.log(name + "-bye");
            //name = rand;
            
            //name += count2;
            ////console.log(name);
            jElem.prop('for', rand+count2);
            count2++;
        });
        var spancheck = $clone.find('.delbtnhead');
          $.each(spancheck, function(index, elem){
                 
              var jElem = $(elem); // jQuery element
              var name = jElem.prop('id');
              name = name.replace(/\d+/g, '');
              // remove the number
              name = 'newspan';
              jElem.prop('id', name);
              jElem.prop('class', 'span1 onedelete');
          });

          var address1 = $clone.find('.address');
          $.each(address1, function(index, elem){
                 
              var jElem = $(elem); // jQuery element
              //jElem.prop('required', true);
          });
         
          var postcode1 = $clone.find('.postcode');
          $.each(postcode1, function(index, elem){
            var jElem = $(elem)
            var name = jElem.prop('id');
            console.log(name);
            name = name.replace(/\d+/g, '');
            name = 'addHeader'+post_count+'-postcode';
            jElem.prop('id', name);
            //   var jElem = $(elem); // jQuery element
              //jElem.prop('required', true);
              post_count++;
              console.log(post_count);
          });
          
        $(document).find('#addSubFields').before($clone);
        $('#addHeader'+countZ+' input[type="text"]').val('');
        autoWrap.indexOf('addHeader'+countZ) === -1 ? autoWrap.push('addHeader'+countZ) : console.log("exists");
          loadMapHeader(autoWrap);
    });
    
    function loadMapHeader(autoWrap){  
       // console.log(autoWrap);
        $.each(autoWrap, function(index, name) {
            const geocoder = new google.maps.Geocoder;

        //console.log(index+'--'+name);
            if($('#'+name).length == 0) { 
                //console.log('blank - ' + name);
                return;
            }
            //maoArray[name] = new google.maps.places.Autocomplete(('.form-control')[0], { types: ['geocode'] }); //console.log('hello');
            maoArray[name] = new google.maps.places.Autocomplete(document.getElementById(name+'-input'), { types: ['geocode'] });
                
            google.maps.event.addListener(maoArray[name], 'place_changed', function() {

                var place = maoArray[name].getPlace();

                geocoder.geocode({'placeId': place.place_id}, function (results, status) {
                    
                    if (status === google.maps.GeocoderStatus.OK) {
                        
                       
                        const lat = results[0].geometry.location.lat();
                        const lng = results[0].geometry.location.lng();
                        //console.log(name+'-input');
                        document.getElementById(name + '-latitude').value = lat;
                        document.getElementById(name + '-longitude').value = lng;
                        const postCode = results[0].address_components.find(addr => addr.types[0] === "postal_code").short_name;
                        document.getElementById(name + '-postcode').value = postCode;
                    }
                });
            });
        });
    }

    $(document).on('click', ".onedelete", function() {
                
        $(this).closest(".copyin").remove();
    });

    /*$(document).on('click', ".onedelete", function() {
                
        $(this).closest(".copyin").remove();
    });

    subTaskHeader*/

    $(document).on("click", "input[type='radio'].check", function() {

          var dateredio = $("#dateredio input[type='radio']:checked").val();
          if (dateredio == 'schedule') {
            $(".datenow").show();
          }else{
              $(".datenow").hide();
          }
          
      });

      
      $(document).on('click', "#taskschedule", function() {
                
            var dateredio = $("#dateredio input[type='radio']:checked").val();
                if (dateredio == 'schedule') {
                    $(".opendatepicker").focus();
                }
        });

    $(document).on("click", ".submitTaskHeader", function(e) {
      e.preventDefault();
    //$("#taskFormHeader").bind("submit", function() { 
      var err = 0;
        $(".addspan").hide();
        $(".tagspan").hide();
        $(".tagspan2").hide();
        $(".searchspan").hide();

        var cus_id = $('#cusid').val();
        var name = $('#name_new').val();
        var email = $('#email_new').val();
        var phone_no = $('#phone_new').val();

      
        console.log(cus_id + ' - '+ name + '-' + phone_no + '-' + email);

        if (cus_id == '') {
            if (name != '' && email != '' && phone_no != '') {

            } else {  err = 1;
                $(".searchspan").show();
                return false;
            }
        }
       //return false;
        var selectedVal = "";
        var selected = $("#typeInputss input[type='radio']:checked");
        selectedVal = selected.val();
        //console.log(selectedVal);
        if (typeof(selectedVal) == "undefined") {
            var short_name = $("#task-modal-header input[name=short_name").val();
            var address = $("#task-modal-header input[name=address]").val();
            var post_code = $("#task-modal-header input[name=post_code]").val();
            if (short_name != '' && address != '' && post_code != '') {

            } else {  err = 1;
                $(".addspan").show();
                return false;
            }
        }

        var autoval = "";
        var auto = $("#rediodiv input[type='radio']:checked");
        autoval = auto.val();
        //console.log(autoval);
        // if (autoval == 'auto' || autoval == 'a') {
        //     var value = $("#selectize-optgroups option:selected").text();
        //     var value2 = $("#selectize-optgroup option:selected").text();
        //     if (value == '') {
        //         $(".tagspan").show();  err = 1;
        //         return false;
        //     }
        //     if (value2 == '') {
        //         $(".tagspan").show();  err = 1;
        //         return false;
        //     }
        // }
        if( err == 0){
            var formData = new FormData(document.querySelector("#taskFormHeader"));
            TaskSubmit(formData, 'POST', '/newtasks', '#task-modal-header');            
          //$("#taskFormHeader").submit();
        }
    });

    

    function TaskSubmit(data, method, url, modals) {
    //alert(data);
    $.ajax({
        method: method,
        headers: {
            Accept: "application/json"
        },
        url: url,
        data: data,
        contentType: false,
        processData: false,
        success: function(response) {
            //alert(response)
            if (response) {
                    $("#task-modal-header .close").click();
                         location.reload();
            } else {
                $(".show_all_error.invalid-feedback").show();
                $(".show_all_error.invalid-feedback").text(response.message);
            }
            //return response;
        },
        error: function(response) {
            
        }
    });
}

    // show proofs initial check 
    // $(document).ready(function() {
    //     $('.selecttype').val("1").click();
    // });
    $(document).on('keyup', ".onlynumber", function() { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
    });
    // $(document).on("change", "#file", function() {
    //    previewImages(this);
    // });

    //on select of task type

    $(document).on('change', ".selecttype", function() { 
        
            // proof = task_proofs[this.value-1].barcode;

            // if(proof != 0){

            //  $(".barcode").show();

            // }else{
            //     $(".barcode").hide();
            // }

        if (this.value == 3){
           $span = $(this).closest(".firstclone1").find(".appoint").show();
           //console.log($span); 
        }   
        else{
            $(this).closest(".firstclone1").find(".appoint").hide();
        }
    });

    $(document).on("click", "#file", function() {
      $('.showsimagegall').hide();
      $('.imagepri').remove();
       //readURL(this);
    });

    $(document).on("change", "#file", function() {
       previewImages(this);
    });

    function previewImages(input) { //console.log('1');

      var fileList = input.files;
      if(fileList.length == 0){
        $('.showsimagegall').show();
      }
      var anyWindow = window.URL || window.webkitURL; //console.log('2');

      for(var i = 0; i < fileList.length; i++){
         var objectUrl = anyWindow.createObjectURL(fileList[i]);
         $('#imagePreview').append('<img src="' + objectUrl + '" class="imagepri" />');
         window.URL.revokeObjectURL(fileList[i]);
       }
    }
   
    $(document).on('click', '.assignRadio', function () {

        var radioValue = $("#rediodiv input[type='radio']:checked").val();
        if (radioValue == 'a') {
           
            $( ".tags" ).removeClass("hidealloction");
            $( ".drivers" ).addClass("hidealloction");
            // $(".tags").show();
            // $(".drivers").hide();
        }
        if (radioValue == 'u') {
            $( ".tags" ).addClass("hidealloction");
            $( ".drivers" ).addClass("hidealloction");
            // $(".tags").hide();
            // $(".drivers").hide();
        }
        if (radioValue == 'm') {
            $( ".drivers" ).removeClass("hidealloction");
            $( ".tags" ).addClass("hidealloction");
            // $(".drivers").show();
            // $(".tags").hide();
        }
    });

    $('#show-map-Header').on('hide.bs.modal', function () {
         $('#task-modal-header').removeClass('fadeIn');

    });

    $(document).on('click', '.showMapHeader', function(){
        var no = $(this).attr('num');
        var lats = document.getElementById(no+'-latitude').value;
        var lngs = document.getElementById(no+'-longitude').value;

        document.getElementById('map_for_header').value = no;

        if(lats == null || lats == '0'){
            lats = 28.6862738;
        }
        if(lngs == null || lngs == '0'){
            lngs = 77.2217831;
        }

        var myLatlng = new google.maps.LatLng(lats, lngs);
            var mapProp = {
                center:myLatlng,
                zoom:13,
                mapTypeId:google.maps.MapTypeId.ROADMAP
              
            };
            var map=new google.maps.Map(document.getElementById("googleMapHeader"), mapProp);
                var marker = new google.maps.Marker({
                  position: myLatlng,
                  map: map,
                  title: 'Hello World!',
                  draggable:true  
              });
            document.getElementById('lat_map_header').value= lats;
            document.getElementById('lng_map_header').value= lngs ; 
            // marker drag event
            google.maps.event.addListener(marker,'drag',function(event) {
                document.getElementById('lat_map_header').value = event.latLng.lat();
                document.getElementById('lng_map_header').value = event.latLng.lng();
            });

            //marker drag event end
            google.maps.event.addListener(marker,'dragend',function(event) {
                var zx =JSON.stringify(event);
                //console.log(zx);


                document.getElementById('lat_map_header').value = event.latLng.lat();
                document.getElementById('lng_map_header').value = event.latLng.lng();
                //alert("lat=>"+event.latLng.lat());map_for_header
                //alert("long=>"+event.latLng.lng());
            });
            $('#task-modal-header').addClass('fadeIn');
        $('#show-map-Header').modal({
            //backdrop: 'static',
            keyboard: false
        });

    });

    $(document).on('click', '.selectMapOnHeader', function () {

        var mapLat = document.getElementById('lat_map_header').value;
        var mapLlng = document.getElementById('lng_map_header').value;
        var mapFor = document.getElementById('map_for_header').value;
        //console.log(mapLat+'-'+mapLlng+'-'+mapFor);
        document.getElementById(mapFor + '-latitude').value = mapLat;
        document.getElementById(mapFor + '-longitude').value = mapLlng;


        $('#show-map-Header').modal('hide');
    });


    




    if(theme['theme'] == 'dark'){
        
            var themeType = [
                { elementType: "geometry", stylers: [{ color: "#242f3e" }] },
                {
                elementType: "labels.text.stroke",
                stylers: [{ color: "#242f3e" }],
                },
                {
                elementType: "labels.text.fill",
                stylers: [{ color: "#746855" }],
                },
                {
                featureType: "administrative.locality",
                elementType: "labels.text.fill",
                stylers: [{ color: "#d59563" }],
                },
                {
                featureType: "poi",
                elementType: "labels.text.fill",
                stylers: [{ color: "#d59563" }],
                },
                {
                featureType: "poi.park",
                elementType: "geometry",
                stylers: [{ color: "#263c3f" }],
                },
                {
                featureType: "poi.park",
                elementType: "labels.text.fill",
                stylers: [{ color: "#6b9a76" }],
                },
                {
                featureType: "road",
                elementType: "geometry",
                stylers: [{ color: "#38414e" }],
                },
                {
                featureType: "road",
                elementType: "geometry.stroke",
                stylers: [{ color: "#212a37" }],
                },
                {
                featureType: "road",
                elementType: "labels.text.fill",
                stylers: [{ color: "#9ca5b3" }],
                },
                {
                featureType: "road.highway",
                elementType: "geometry",
                stylers: [{ color: "#746855" }],
                },
                {
                featureType: "road.highway",
                elementType: "geometry.stroke",
                stylers: [{ color: "#1f2835" }],
                },
                {
                featureType: "road.highway",
                elementType: "labels.text.fill",
                stylers: [{ color: "#f3d19c" }],
                },
                {
                featureType: "transit",
                elementType: "geometry",
                stylers: [{ color: "#2f3948" }],
                },
                {
                featureType: "transit.station",
                elementType: "labels.text.fill",
                stylers: [{ color: "#d59563" }],
                },
                {
                featureType: "water",
                elementType: "geometry",
                stylers: [{ color: "#17263c" }],
                },
                {
                featureType: "water",
                elementType: "labels.text.fill",
                stylers: [{ color: "#515c6d" }],
                },
                {
                featureType: "water",
                elementType: "labels.text.stroke",
                stylers: [{ color: "#17263c" }],
                },
                {
                featureType: "poi",
                elementType: "labels",
                stylers: [
                    { visibility: "off" }
                ]
            },
            ];
   
    }else{
        themeType = [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [
                    { visibility: "off" }
                ]
            }
        ];
    }

    $('.onlynumber').keyup(function ()
        {    
        this.value = this.value.replace(/[^0-9\.]/g,'');
        });

    
</script>