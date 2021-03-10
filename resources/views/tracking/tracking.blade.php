<!doctype html>
<html lang="en">

<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name=”format-detection” content=”telephone=no”>
   <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
   <!-- Bootstrap CSS -->
   <!-- <link rel="stylesheet" href="css/fontawesome.css"> -->
   <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
   <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap"
      rel="stylesheet">
   <link rel="stylesheet" href="{{asset('tracking/css/bootstrap.css')}}">
   <link rel="stylesheet" href="{{asset('tracking/css/style.css')}}">
   <link rel="stylesheet" href="{{asset('tracking/css/responsive.css')}}">
   <title>Customer Ordering Home</title>
</head>

<body >

   <!--location Area -->
   <section class="location_wrapper py-xl-5 position-relative d-lg-flex align-items-lg-center">
      <div class="container px-0">
         <div class="row no-gutters">
            <div class="col-12">
               <div class="map_box">
                  <iframe
                     src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d109782.78861272808!2d76.95784163044429!3d30.698374220997263!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1615288788406!5m2!1sen!2sin"
                     width="100%" style="border:0;" allowfullscreen="" loading="lazy"  scrolling="no"></iframe>
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
                           <img src="images/slider01.jpg" alt="" />
                        </div>
                        <h4>Greg Crane</h4>
                        <p>Urban Deliveries</p>
                     </div>
                     <div class="col-lg-4 d-lg-flex align-items-center address_box mb-3 attrbute_classes">
                        <i class="fas fa-car-side"></i>
                        <div class="right_text">
                           <h4>2012 Volkswagen Golf R</h4>
                           <p>Blue, Plate 243TVP</p>
                        </div>
                     </div>
                     <div class="col-lg-4 d-lg-flex align-items-center address_box mb-3 attrbute_classes">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="right_text">
                           <h4>87 Lafayette St</h4>
                           <p><b>Apartment 308</b></p>
                           <p>New York, NY 10013</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            
            <div class="row no-gutters">
               <div class="offset-sm-3 col-sm-6 btn_group d-flex align-items-center justify-content-between">
                  <a class="btn pink_btn" href="#"><i
                        class="fas fa-phone-alt position-absolute"></i><span>Call</span></a>
                  <a class="btn pink_btn" href="#"><i
                        class="fas fa-comment position-absolute"></i><span>Message</span></a>
               </div>
            </div>
         </div>
      </div>
   </section>



   <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="{{asset('tracking/js/jquery-min.js')}}"></script>
   <script src="{{asset('tracking/js/common.js')}}"></script>
   <script src="{{asset('tracking/js/bootstrap.min.js')}}"></script>
</body>

</html>
.