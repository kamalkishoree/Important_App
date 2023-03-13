<script type="text/javascript">
    var autocomplete = {};
    var autocompletesWraps = ['address'];
    $(document).ready(function(){
        loadMap(autocompletesWraps);
    });
    $('.openModal').click(function(){
        $('#add-amenity-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    //google please code
    $(document).on('click', '.showMap', function() {
        var no = $(this).attr('num');
        var lats = document.getElementById('latitude').value;
        var lngs = document.getElementById('longitude').value;
        var address = document.getElementById('address').value;

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
        document.getElementById('address_map').value = address;
        var infowindow = new google.maps.InfoWindow();
        var geocoder = new google.maps.Geocoder();
        // marker drag event
        // google.maps.event.addListener(marker, 'drag', function(event) {
        //     document.getElementById('lat_map').value = event.latLng.lat();
        //     document.getElementById('lng_map').value = event.latLng.lng();
        // });

        google.maps.event.addListener(marker, 'dragend', function() {
            geocoder.geocode({
            'latLng': marker.getPosition()
            }, function(results, status) {

            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                        document.getElementById('lat_map').value = marker.getPosition().lat();
                        document.getElementById('lng_map').value = marker.getPosition().lng();
                        document.getElementById('address').value= results[0].formatted_address;

                    infowindow.setContent(results[0].formatted_address);

                    infowindow.open(map, marker);
                }
            }
            });
        });
        //marker drag event end
        // google.maps.event.addListener(marker, 'dragend', function(event) {
        //     var zx = JSON.stringify(event);
        //     // console.log(zx);


        //     document.getElementById('lat_map').value = event.latLng.lat();
        //     document.getElementById('lng_map').value = event.latLng.lng();
        //     //alert("lat=>"+event.latLng.lat());
        //     //alert("long=>"+event.latLng.lng());
        // });
        $('#add-customer-modal').addClass('fadeIn');
        $('#show-map-modal').modal({
            //backdrop: 'static',
            keyboard: false
        });
    });

    $(document).on('click', '.selectMapLocation', function() {
        var mapLat = document.getElementById('lat_map').value;
        var mapLlng = document.getElementById('lng_map').value;
        var mapFor = document.getElementById('map_for').value;
        
        //console.log(mapLat+'-'+mapLlng+'-'+mapFor);
        document.getElementById('latitude').value = mapLat;
        document.getElementById('longitude').value = mapLlng;
        $('#show-map-modal').modal('hide');
    });

    // $(document).on('click', '.selectMapOnHeader', function () {
        
    //     var mapLat = document.getElementById('lat_map_header').value;
    //     var mapLlng = document.getElementById('lng_map_header').value;
    //     var mapFor = document.getElementById('map_for_header').value;
    //     var address = document.getElementById('addredd_map_header').value;
        
    //     document.getElementById(mapFor + '-latitude').value = mapLat;
    //     document.getElementById(mapFor + '-longitude').value = mapLlng;
    //     document.getElementById(mapFor + '-input').value = address;

    //     $('#show-map-Header').modal('hide');
    // });

    var latitudes = [];
    var longitude = [];

    function loadMap(autocompletesWraps) {
        $.each(autocompletesWraps, function(index, name) {
            const geocoder = new google.maps.Geocoder;

            if ($('#' + name).length == 0) {
                return;
            }
            //autocomplete[name] = new google.maps.places.Autocomplete(('.form-control')[0], { types: ['geocode'] }); console.log('hello');
            autocomplete[name] = new google.maps.places.Autocomplete(document.getElementById(name), {
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

                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lng;
                        const zip_code = results[0].address_components.find(addr => addr.types[0] === "postal_code").short_name;
                        document.getElementById(name + '-postcode').value = zip_code;
                        document.getElementById(name + '-postcode').value = zip_code;

                    }
                });
            });
        });
    }
    $(document).ready(function(){
        $("#category").select2({
            allowClear: true,
            width: "resolve",
            placeholder: "Select Category"
        });
    });
</script>