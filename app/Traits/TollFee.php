<?php
namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use GuzzleHttp\Client;
use App\Model\ClientPreference;
use Log;
use Unifonic;
trait TollFee{

    public function __construct()
    {
        //
    }    
    /**
     * toll_fee
     *
     * @return void
     */
    public function toll_fee($latitude = array(), $longitude = array())
    {
        $ClientPreference = ClientPreference::where('id', 1)->first();
 
        //Sample Api location input
        $origin = [
            'latitude'=> '19.076090',
            'longitude' => '72.877426'
        ];
        $destination = [
            'latitude'=> '28.613939',
            'longitude' => '77.209023'
        ];
        //intermediate destinations
        $waypoints = [
            
        ];
        
        for($i = 0;$i < count($latitude); $i++)
        {
            if($i == 0)
            {
                $origin['latitude'] = $latitude[$i];
                $origin['longitude'] = $longitude[$i];
            }
            if($i > 0 && (count($latitude)-1) > $i){
                $waypoints[$i]['location']['latLng']['latitude'] = $latitude[$i];
                $waypoints[$i]['location']['latLng']['longitude'] = $longitude[$i];
            }
            if((count($latitude)-1) == $i)
            {
                $destination['latitude'] = $latitude[$i];
                $destination['longitude'] = $longitude[$i];
            }
        }

        $headers = array('X-Goog-Api-Key: '.(!empty($ClientPreference)?$ClientPreference->toll_key:''),
                'Content-Type: application/json',
                'X-Goog-FieldMask: routes.duration,routes.distanceMeters,routes.travelAdvisory.tollInfo,routes.legs.travelAdvisory.tollInfo',
                );
                
        $url = "https://routes.googleapis.com/directions/v2:computeRoutes";

        $data = [
                'origin' => [
                    'location' => [
                        'latLng' => $origin
                    ]
                ],
                'destination' => [
                    'location' => [
                        'latLng' => $destination
                    ]
                ],
                'intermediates' => $waypoints,
                "travelMode" => 'TAXI',
                "units" =>  "metric",
                "route_modifiers" => [
                    "vehicle_info" => [
                        "emission_type" => "GASOLINE"
                    ],
                    "toll_passes" => "IN_FASTAG"
                ]
            ];
        
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            // Receive server response ...
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $apiResponse = curl_exec($ch);
            
            curl_close($ch);
            $apiResponse = json_decode($apiResponse);

            $toll_array = array();
            $toll_array['distanceMeters'] = 0;
            $toll_array['duration'] = 0;
            $toll_array['currency'] = '';
            $toll_array['toll_amount'] = 0;
            if(!empty($apiResponse->routes))
            {
                foreach($apiResponse->routes as $routes){
                    $toll_array['distanceMeters'] = $routes->distanceMeters;
                    $toll_array['duration']       = $routes->distanceMeters;
                    foreach($routes->travelAdvisory->tollInfo->estimatedPrice as $estimatedPrice){
                        $toll_array['currency'] = $estimatedPrice->currencyCode;
                        $toll_array['toll_amount'] = $estimatedPrice->units;
                    };
                }
            }else{
                $toll_array['distanceMeters'] = 0;
                $toll_array['duration'] = 0;
                $toll_array['currency'] = '';
                $toll_array['toll_amount'] = 0;
            }
            return $toll_array;
    }


   

}
