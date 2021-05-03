<!DOCTYPE html>
<html lang="en">
    <head>
        

    </head>

    <body>
        <div id="generatyepdf">
            <div class="pathpref"> <span>Desired Path : </span> {{ implode(' --> ',$path) }}</div>
            <ul>
                <?php 
                    foreach ($route as $singleroute) { ?>
                        <li>{!! $singleroute['turn'] !!} Distance : {{$singleroute['distance']}}  Time : {{$singleroute['duration']}}</li>
                    <?php }
                
                ?>
                
            </ul>
        </div>
    </body>
</html>