<div class="row">

    <div class="col-md-6">
        <div class="card-box">
            <h4 class="header-title mb-3"></h4>
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" id="nameInput">
                        {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>

                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" id="make_modelInput">
                        {!! Form::label('title', 'Email',['class' => 'control-label']) !!}
                        {!! Form::email('email', null, ['class' => 'form-control']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" id="make_modelInput">
                        {!! Form::label('title', 'Phone Number',['class' => 'control-label']) !!}
                        {!! Form::text('phone_number', null, ['class' => 'form-control']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
            </div>
            <div class="addapp"> 
                {!! Form::label('title', 'Address',['class' => 'control-label']) !!} 
                <div class="row address" id="addres_div1">
                    <div class="col-md-4">
                        <div class="form-group" id=""> 
                            <input type="text" name="short_name[]" class="form-control" placeholder="Short Name">
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" id="location">
                            <input type="text" id="address-input" name="address[]" class="autocomplete form-control address-input1" placeholder="Address">
                            <span class="invalid-feedback" role="alert" id="location">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" id="">
                            <input type="text" name="post_code[]" class="form-control" placeholder="Post Code">
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="address_latitude" name="latitude" id="latitude" value="0" />
                            <input type="hidden" name="address_longitude" name="longitude" id="longitude" value="0" />
            <div id="address-map-container" style="width:100%;height:400px; display: none;">
                    <div style="width: 100%; height: 100%" id="address-map"></div>
            </div>
            <div class="row">
                <div class="col-md-4">

                </div>
                <div class="col-md-8" id="adds">
                    <a href="#"  class="btn btn-success btn-rounded waves-effect waves-light" >Add More Address</a>
                </div>
            </div>


            <div class="row">
                <div class="col-md-5">
                    <button type="submit" class="btn btn-blue waves-effect waves-light ">Submit</button>
                </div>
                <div class="col-md-7">
                    
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB85kLYYOmuAhBUPd7odVmL6gnQsSGWU-4&libraries=places&callback=initialize" async defer></script>
<script>

    var autocomplete = {};
    var autocompletesWraps = ['addres_div1'];
    var count = 1;
    $(document).ready(function(){
        //initialize();
        loadMap(autocompletesWraps);
    });

    $(document).on('click', '#adds a', function(){
        count = count + 1;

        $(document).find('#address-map-container').before('<div class="row address" id="addres_div'+count+'"><div class="col-md-4"><div class="form-group" id=""><input type="text"  class="form-control" placeholder="Short Name" name="short_name[]"></div></div><div class="col-md-4"><div class="form-group" id=""><input type="text" id="address-input'+count+'" name="address[]" class="autocomplete form-control address-input'+count+'" placeholder="Address"></div></div><div class="col-md-4"><div class="form-group" id=""><input type="text"  class="form-control" placeholder="Post Code" name="post_code[]"></div></div></div>');

        autocompletesWraps.indexOf('addres_div'+count) === -1 ? autocompletesWraps.push('addres_div'+count) : console.log("This item already exists");
        
        //console.log(autocompletesWraps);
        loadMap(autocompletesWraps);

    });

    function loadMap(autocompletesWraps){

        var latitudes = [];
        var longitude = [];
        $.each(autocompletesWraps, function(index, name) {
            const geocoder = new google.maps.Geocoder;
        
            if($('#'+name).length == 0) {
                return;
            }
            

            autocomplete[name] = new google.maps.places.Autocomplete($('#'+name+' .autocomplete')[0], { types: ['geocode'] });
                
            google.maps.event.addListener(autocomplete[name], 'place_changed', function() {
                
                var place = autocomplete[name].getPlace();

                geocoder.geocode({'placeId': place.place_id}, function (results, status) {
                    
                    if (status === google.maps.GeocoderStatus.OK) {
                        const lat = results[0].geometry.location.lat();
                        const lng = results[0].geometry.location.lng();

                        latitudes.push(lat);
                        longitude.push(lng);
                        console.log(latitudes);
                        $('.addapp').find("#latitude").val(lat);
                        $('.addapp').find("#longitude").val(lng);
                    }
                });
            });
        });

    }
</script>