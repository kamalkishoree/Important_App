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

        $origin = [
            'latitude'=> '19.076090',
            'longitude' => '72.877426'
        ];
        $destination = [
            'latitude'=> '28.613939',
            'longitude' => '77.209023'
        ];
        
        for($i = 0;$i < count($latitude); $i++)
        {
            if($i==0)
            {
                $origin['latitude'] = $latitude[$i];
                $origin['longitude'] = $longitude[$i];
            }else{
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
                'intermediates' => [

                ],
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
            // In real life you should use something like:
            // curl_setopt($ch, CURLOPT_POSTFIELDS, 
            //          http_build_query(array('postvar1' => 'value1')));
            
            // Receive server response ...
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $server_output = curl_exec($ch);
            
            curl_close ($ch);
            return $server_output;
            //pr($server_output);
    }


   

}
