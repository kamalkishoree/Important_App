<script>
    var autocomplete = {};
    var autocompletesWraps = ['add0'];
    var count = 1;
    editCount = 0;
    $('.openModal').click(function() {
        $('#add-customer-modal').modal({
            //backdrop: 'static',
            keyboard: false
        });
        autocompletesWraps.push('add1');
        loadMap(autocompletesWraps);
    });

    //google please code
    $(document).on('click', '.showMap', function() {
        var no = $(this).attr('num');
        var lats = document.getElementById(no + '-latitude').value;
        var lngs = document.getElementById(no + '-longitude').value;

        document.getElementById('map_for').value = no;

        if (lats == null || lats == '0') {
            lats = 51.508742;
        }
        if (lngs == null || lngs == '0') {
            lngs = -0.120850;
        }

        var myLatlng = new google.maps.LatLng(lats, lngs);
        var mapProp = {
            center: myLatlng,
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP

        };
        var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: 'Hello World!',
            draggable: true
        });
        document.getElementById('lat_map').value = lats;
        document.getElementById('lng_map').value = lngs;
        // marker drag event
        google.maps.event.addListener(marker, 'drag', function(event) {
            document.getElementById('lat_map').value = event.latLng.lat();
            document.getElementById('lng_map').value = event.latLng.lng();
        });

        //marker drag event end
        google.maps.event.addListener(marker, 'dragend', function(event) {
            var zx = JSON.stringify(event);
            // console.log(zx);


            document.getElementById('lat_map').value = event.latLng.lat();
            document.getElementById('lng_map').value = event.latLng.lng();
            //alert("lat=>"+event.latLng.lat());
            //alert("long=>"+event.latLng.lng());
        });
        $('#add-customer-modal').addClass('fadeIn');
        $('#show-map-modal').modal({
            //backdrop: 'static',
            keyboard: false
        });

    });

    $('#show-map-modal').on('hide.bs.modal', function() {
        $('#add-customer-modal').removeClass('fadeIn');

    });

    $(document).on('click', '.selectMapLocation', function() {
        
        var mapLat = document.getElementById('lat_map').value;
        var mapLlng = document.getElementById('lng_map').value;
        var mapFor = document.getElementById('map_for').value;
        //console.log(mapLat+'-'+mapLlng+'-'+mapFor);
        document.getElementById(mapFor + '-latitude').value = mapLat;
        document.getElementById(mapFor + '-longitude').value = mapLlng;


        $('#show-map-modal').modal('hide');
    });

    $(document).ready(function() {
        $('#pricing-datatable').DataTable();
        loadMap(autocompletesWraps);

    });

    //change status on a customer
    $(function() {
        $('.custom-control-input').change(function() {
            var status = $(this).prop('checked') == true ? "Active" : 'In-Active';
            var user_id = $(this).data('id');

            $.ajax({
                type: "GET",
                dataType: "json",
                url: '/changeStatus',
                data: {
                    'status': status,
                    'id': user_id
                },
                success: function(data) {
                    //console.log(data.success)
                }
            });
        })
    });

    //append new address fields

    $(document).on('click', '.addField', function() {
        count = count + 1;
        var delbtn = "'',"+count;
        $(document).find('#address-map-container').before('<div class="row address addressrow'+ count +'" id="add' + count +
            '"><div class="col-lg-2 col-md-3 mb-lg-0 mb-3"><div class="form-group" id=""><input type="text"  class="form-control" placeholder="Short Name" name="short_name[]"></div></div><div class="col-lg-4 col-md-3 mb-lg-0 mb-3"><div class="form-group input-group" id=""><input type="text" id="add' +
            count +
            '-input" name="address[]" class="autocomplete form-control" placeholder="Address"><div class="input-group-append"><button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="add' +
            count +
            '"> <i class="mdi mdi-map-marker-radius"></i></button></div><input type="hidden" name="latitude[]" id="add' +
            count + '-latitude" value="0" /><input type="hidden" name="longitude[]" id="add' + count +
            '-longitude" value="0" /></div></div><div class="col-lg-2 col-md-3 mb-lg-0 mb-3"><div class="form-group"><input type="text" id="add'+ count +'-email" name="address_email[]" class="form-control" placeholder="Email" value=""><span class="invalid-feedback" role="alert"><strong></strong></span></div></div><div class="col-lg-2 col-md-3 mb-lg-0 mb-3"><div class="form-group"><input type="text" id="add'+ count +'-phone_number" name="address_phone_number[]" class="form-control" placeholder="Phone Number" value=""><span class="invalid-feedback" role="alert"><strong></strong></span></div></div><div class="col-lg-2 col-md-3 mb-lg-0 mb-3"><div class="form-group d-flex align-items-center" id=""><input type="text" id="add' +
            count + '-postcode" class="form-control" placeholder="Post Code" name="post_code[]"><button type="button" class="btn btn-primary-outline action-icon" onclick="deleteAddress('+delbtn+')"> <i class="mdi mdi-delete"></i></button></div></div></div>'
            );

        autocompletesWraps.indexOf('add' + count) === -1 ? autocompletesWraps.push('add' + count) :
            "This item already exists";

        //console.log(autocompletesWraps);
        loadMap(autocompletesWraps);

    });

    var latitudes = [];
    var longitude = [];

    function loadMap(autocompletesWraps) {

        //console.log(autocompletesWraps);
        $.each(autocompletesWraps, function(index, name) {
            const geocoder = new google.maps.Geocoder;

            if ($('#' + name).length == 0) {
                return;
            }
            //autocomplete[name] = new google.maps.places.Autocomplete(('.form-control')[0], { types: ['geocode'] }); console.log('hello');
            autocomplete[name] = new google.maps.places.Autocomplete(document.getElementById(name + '-input'), {
                types: ['geocode']
            });

            google.maps.event.addListener(autocomplete[name], 'place_changed', function() {

                var place = autocomplete[name].getPlace();

                geocoder.geocode({
                    'placeId': place.place_id
                }, function(results, status) {

                console.log(results);

                    if (status === google.maps.GeocoderStatus.OK) {
                       console.log('hello');
                       
                        const lat = results[0].geometry.location.lat();
                        const lng = results[0].geometry.location.lng();
                        
                        //console.log(latitudes);
                        document.getElementById(name + '-latitude').value = lat;
                        document.getElementById(name + '-longitude').value = lng;
                        const zip_code = results[0].address_components.find(addr => addr.types[0] === "postal_code").short_name;
                        document.getElementById(name + '-postcode').value = zip_code;
                        
                    }
                });
            });
        });

    }

    //edit customer
    $(".editIcon").click(function(e) {


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
            success: function(data) {

               // $('.page-title1').html('Hello');
                //console.log('data');

                $('#edit-customer-modal #editCardBox').html(data.html);
                $('#edit-customer-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                editCount = data.addFieldsCount;
                for (var i = 1; i <= data.addFieldsCount; i++) {
                    autocompletesWraps.push('edit' + i);
                    loadMap(autocompletesWraps);
                }

            },
            error: function(data) {
                // console.log('data2');
            }
        });
    });

    //multi address edit
    $(document).on('click', '.editInput', function() {
        editCount = editCount + 1;
        var delbtn = "'',"+editCount;
        $(document).find('#editAddress-map-container').before('<div class="row address addEditAddress addressrow'+ editCount +'" id="edit' + editCount +
            '"><div class="col-lg-2 col-md-3 mb-lg-0 mb-3"><div class="form-group" id=""><input type="text"  class="form-control" placeholder="Short Name" name="short_name[]"></div></div><div class="col-lg-4 col-md-3 mb-lg-0 mb-3"><div class="form-group input-group" id=""><input type="text" id="edit' +
            editCount +
            '-input" name="address[]" class="autocomplete form-control" placeholder="Address"><div class="input-group-append"><button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="edit' +
            editCount +
            '"> <i class="mdi mdi-map-marker-radius"></i></button></div><input type="hidden" name="latitude[]" id="edit' +
            editCount + '-latitude" value="0" /><input type="hidden" name="longitude[]" id="edit' +
            editCount +
            '-longitude" value="0" /></div></div><div class="col-lg-2 col-md-3 mb-lg-0 mb-3"><div class="form-group"><input type="text" id="edit'+ editCount +'-email" name="address_email[]" class="form-control" placeholder="Email" value=""><span class="invalid-feedback" role="alert"><strong></strong></span></div></div><div class="col-lg-2 col-md-3 mb-lg-0 mb-3"><div class="form-group"><input type="text" id="edit'+ editCount +'-phone_number" name="address_phone_number[]" class="form-control" placeholder="Phone Number" value=""><span class="invalid-feedback" role="alert"><strong></strong></span></div></div><div class="col-lg-2 col-md-3 mb-lg-0 mb-3"><div class="form-group delete_btn d-flex align-items-center" id=""><input type="text" id="edit' +
                editCount + '-postcode" class="form-control" placeholder="Post Code" name="post_code[]"><button type="button" class="btn btn-primary-outline action-icon" onclick="deleteAddress('+delbtn+')"> <i class="mdi mdi-delete"></i></button></div></div></div>'

            );

        autocompletesWraps.indexOf('edit' + editCount) === -1 ? autocompletesWraps.push('edit' + editCount) :
            "This item already exists";

        //console.log(autocompletesWraps);
        loadMap(autocompletesWraps);

    });

    /* add customer using ajax*/
    $("#add-customer-modal #add_customer").submit(function(e) {
        e.preventDefault();
    });

    $(document).on('click', '.submitCustomerForm', function() {
        var form = document.getElementById('add_customer');
        var formData = new FormData(form);
        var urls = "{{ URL::route('customer.store') }}";
        saveCustomer(urls, formData, inp = '', modal = 'add-customer-modal');
    });

    $("#edit-customer-modal #edit_customer").submit(function(e) {
        e.preventDefault();
    });

    $(document).on('click', '.submitEditForm', function(e) {
        e.preventDefault();
        var form = document.getElementById('edit_customer');
        var formData = new FormData(form);
        var urls = document.getElementById('customer_id').getAttribute('url');
        saveCustomer(urls, formData, inp = 'Edit', modal = 'edit-customer-modal');
        //console.log(urls);
    });

    //ajax for save data
    function saveCustomer(urls, formData, inp = '', modal = '') {

        $.ajax({
            method: 'post',
            headers: {
                Accept: "application/json"
            },
            url: urls,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status == 'success') {
                    $("#" + modal + " .close").click();
                    location.reload();
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            error: function(response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $("#" + key + "Input" + inp + " input").addClass("is-invalid");
                        $("#" + key + "Input" + inp + " span.invalid-feedback").children("strong")
                            .text(errors[key][0]);
                        $("#" + key + "Input span.invalid-feedback").show();
                    });
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                }
                return response;
            }
        });

    }

    function deleteAddress(id,rowid)
    {
        if(id!="")
        {
            $.ajax({
                type: 'POST',            
                url: '{{url("/remove-location")}}',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                data: {'locationid':id},

                success: function(response) {
                    if(response=="removed")
                    {
                        $('.addressrow'+rowid).remove();
                    }else{
                        alert('Try again later');
                    }
                },
                error: function(response) {
                    alert('There is some issue. Try again later');
                    // $('.pageloader').css('display','none');
                }
            });
        }else{
            $('.addressrow'+rowid).remove();
        }
        

    }

</script>
