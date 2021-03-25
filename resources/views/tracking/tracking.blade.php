<!doctype html>
<html lang="en">

<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name=”format-detection” content=”telephone=no”>
   <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/images/favicon.ico')}}">
   <!-- Bootstrap CSS -->
   <!-- <link rel="stylesheet" href="css/fontawesome.css"> -->
   <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
   <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap"
      rel="stylesheet">
   <link rel="stylesheet" href="{{asset('tracking/css/bootstrap.css')}}">
   <link rel="stylesheet" href="{{asset('tracking/css/style.css')}}">
   <link rel="stylesheet" href="{{asset('tracking/css/responsive.css')}}">
   <title>Royo Order Tracking</title>
</head>
@php
    $task_type_array = ['Pickup','Drop-Off','Appointment']
@endphp
<body >

   <!--location Area -->
   <section class="location_wrapper py-xl-5 position-relative d-lg-flex align-items-lg-center">
      <div class="container px-0">
         <div class="row no-gutters">
            <div class="col-12">
               <div class="map_box">
                  <div style="width: 100%">
                     <div id="map_canvas"></div>
                  </div>
                  {{-- <iframe
                     src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d109782.78861272808!2d76.95784163044429!3d30.698374220997263!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1615288788406!5m2!1sen!2sin"
                     width="100%" style="border:0;" allowfullscreen="" loading="lazy"  scrolling="no"></iframe> --}}
               </div>
            </div>
         </div>
         <div class="location_box px-3 position-relative get_div_height">
            <div class="row">
               <div class="offset-lg-0 col-lg-12 offset-md-2 col-md-8">
                  <i class="fas fa-chevron-up detail_btn d-lg-none d-block show_attr_classes"></i>
                  <div class="row no-gutters align-items-center" >
                     <div class="col-lg-4 padd-left mb-3" >
                        <div class="left-icon">
                           <img src="{{ 'https://imgproxy.royodispatch.com/insecure/fit/300/100/sm/0/plain/'.Storage::disk('s3')->url($order->profile_picture ?? 'assets/client_00000051/agents605b6deb82d1b.png/XY5GF0B3rXvZlucZMiRQjGBQaWSFhcaIpIM5Jzlv.jpg')}}" alt="" />
                        </div>
                        <h4>{{isset($order->name) ? $order->name: 'Driver not assigned yet'}}</h4>
                        <p>{{$order->phone_number}}</p>
                     </div>
                     <span class="col-lg-8 attrbute_classes">
                        <div class="row align-items-center">
                     @foreach ($tasks as $item)
                        <div class="col-lg-6 d-flex align-items-center address_box mb-3">
                           <i class="fas fa-map-marker-alt"></i>
                           <div class="right_text">
                              <h4>{{$task_type_array[$item->task_type_id-1]}}</h4>
                              <p>{{$item->address}}</p>
                           </div>
                        </div> 
                     @endforeach
                     </span>
                  </div>
               </div> 
               </div>
            </div>
            
            <div class="row no-gutters">
               <div class="offset-sm-3 col-sm-6 btn_group d-flex align-items-center justify-content-between">
                  <a class="btn pink_btn" href="tel:{{$order->phone_number}}"><i
                        class="fas fa-phone-alt position-absolute"></i><span>Call</span></a>
                  <a class="btn pink_btn" href="sms:{{$order->phone_number}}"><i
                        class="fas fa-comment position-absolute"></i><span>Message</span></a>
               </div>
            </div>
         </div>
      </div>
   </section>
   

   <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="{{asset('tracking/js/jquery-min.js')}}"></script>
   <script defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB85kLYYOmuAhBUPd7odVmL6gnQsSGWU-4&libraries=places,drawing,visualization&v=weekly">
        </script>
   <script src="{{asset('tracking/js/common.js')}}"></script>
   <script src="{{asset('tracking/js/bootstrap.min.js')}}"></script>
   <script>
      var alltask  = {!!json_encode($tasks)!!};

      $(document).ready(function() {

	     initMap();
	  
     });

        console.log(alltask);

         var url = window.location.origin;
         let labelIndex = 0;
         var theme = {featureType: "poi",
                     elementType: "labels",
                     stylers: [
                     { visibility: "off" }
                     ] };
      const haightAshbury = {
               // lat: 30.7046,
               // lng: 76.7179
               lat: alltask[0].latitude && alltask[0].latitude  != "0.00000000" ? parseFloat(alltask[0].latitude): 30.7046,
               lng: alltask[0].longitude && alltask[0].longitude != "0.00000000" ? parseFloat(alltask[0].longitude):76.7179
         };
         function initMap() {
         const bangalore = { lat: 30.75026050, lng: 76.63973400 };
         const map = new google.maps.Map(document.getElementById("map_canvas"), {
            zoom: 11,
            center: haightAshbury,
            mapTypeId: "roadmap",
         });
         // This event listener calls addMarker() when the map is clicked.
         google.maps.event.addListener(map, "click", (event) => {
            addMarker(event.latLng, map);
         });
         alltask.forEach(element => {
          var image = url+'/assets/newicons/'+element.task_type_id+'.png'
            
            addMarker({lat: parseFloat(element.latitude),lng:  parseFloat(element.longitude)},map,image);
            
         });
         // Add a marker at the center of the map.
         
         }
         
         // Adds a marker to the map.
         function addMarker(location,map,image) {
         // Add the marker at the clicked location, and add the next-available label
         // from the array of alphabetical characters.
         new google.maps.Marker({
            position: location,
            label: null,
            icon:image,
            map: map,
            styles: theme
         });
         }

   </script>
</body>

</html>
