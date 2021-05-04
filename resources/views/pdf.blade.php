<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name=”format-detection” content=”telephone=no”>
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
        <!-- Bootstrap CSS -->
        <!-- <link rel="stylesheet" href="css/fontawesome.css"> -->
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('tracking/css/bootstrap.css') }}">
        
        <link rel="stylesheet" href="{{ asset('tracking/css/responsive.css') }}">
        <title>Royo Generate Path</title>

    </head>

    <body>
        <style>

                #generatyepdf{
                    max-width: 600px;
                   
                }
        </style>
        <section class="location_wrapper">
            @php
                    // $urlImg = URL::to('/').'images/users/user-1.jpg';
              
                  if(isset(Auth::user()->logo)){
                    $urlImg = Storage::disk('s3')->url(Auth::user()->logo);
                  }
                  $imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fit/300/100/sm/0/plain/';
                  $image = $imgproxyurl.$urlImg;                  

            @endphp
            
            <div class="logo-box">      
                <span class="logo-lg">
                    <img src="{{$image}}" alt="" height="50" style="padding-top: 4px;">
                </span>
            </div>
            
            <div id="generatyepdf">
                <?php
                    if($agent_name=="")
                    {
                        $pathtitle = "Route for ".$date;
                    }else{
                        $pathtitle = "Route for ".ucfirst($agent_name)." for ".$date;
                    }
                    ?>
                <div class="pdf-path"> <span>{{ $pathtitle }} : </span>
                    <ul class="pdf-path-address">
                        <?php
                            for ($i=0; $i < count($path); $i++) { ?>
                                <li>{{ $path[$i] }}</li>
                            <?php }
                        ?>
                    </ul>
                </div>
                <ul class="list-group">
                    <?php 
                        foreach ($route as $singleroute) { ?>
                            <li class="list-group-item">
                                <div class="pdf-turn">{!! $singleroute->turn !!} </div>
                                <div class="pdf-distance"> <span>Distance : </span>{{$singleroute->distance}} </div> 
                                <div class="pdf-time"> <span>Time : </span> {{$singleroute->duration}}</div> 
                            </li>
                        <?php }
                    
                    ?>
                    
                </ul>
            </div>
        </section>
    </body>
</html>