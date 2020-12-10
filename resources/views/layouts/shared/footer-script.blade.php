<!-- bundle -->
<!-- Vendor js -->
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

<script src="{{asset('assets/js/vendor.min.js')}}"></script>
<script src="{{asset('assets/js/waitMe.min.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB85kLYYOmuAhBUPd7odVmL6gnQsSGWU-4&libraries=places" async defer></script>
<div id="my_mapZX" style="display: none;"></div>

<!--<script src="https://maps.googleapis.com/maps/api/js?key={{session('preferences.map_key_1')}}&libraries=places" async defer> </script> -->


<script>

  $(document).ready(function(){
    checkMap();
  });

  function checkMap() {
    const mapDiv = document.getElementById("my_mapZX");
    const map = new google.maps.Map(mapDiv, {
      zoom: 8,
      center: new google.maps.LatLng(-34.397, 150.644),
    });
  }

const startLoader = function(element) {
    // check if the element is not specified
    if (typeof element == 'undefined') {
        element = "body";
    }
    // set the wait me loader
    $(element).waitMe({
        effect: 'bounce',
        text: 'Please Wait..',
        bg: 'rgba(255,255,255,0.7)',
        //color : 'rgb(66,35,53)',
        color: '#EFA91F',
        sizeW: '20px',
        sizeH: '20px',
        source: ''
    });
}

function gm_authFailure() {
    $('.excetion_keys').append('<span><i class="mdi mdi-block-helper mr-2"></i> <strong>Google Map</strong> key is not valid</span><br/>');
    $('.displaySettingsError').show();
 };

const stopLoader = function(element) {
    // check if the element is not specified
    if (typeof element == 'undefined') {
        element = 'body';
    }
    // close the loader
    $(element).waitMe("hide");
}

function initial(window, google, lat, lng) {
   var options = {
       center: {
           lat: Number(lat),
           lng: Number(lng)
       },
       zoom: 5,
       disableDefaultUI: true,
       scrollwheel: true,
       draggable: false
   },
   element = document.getElementById('map-canvas'),
   map = new google.maps.Map(element, options);
};


</script>
@yield('script')
<!-- App js -->
<script src="{{asset('assets/js/app.min.js')}}"></script>
@yield('script-bottom')