<?php
namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use GuzzleHttp\Client;
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
    public function toll_fee(){
        $headers = array('X-Goog-Api-Key: AIzaSyC3S_hjoHKyXGwpYxVQM-iTXxu9-ap2Ny0',
                'Content-Type: application/json',
                'X-Goog-FieldMask: routes.route.duration,routes.route.distanceMeters,routes.route.travelAdvisory.tollInfo,routes.route.legs.travelAdvisory.tollInfo,fallbackInfo',
                );
                
        $url = "https://routespreferred.googleapis.com/v1alpha:computeRoutes";

        $data = [
                'origin' => [
                    'location' => [
                        'latLng' => [
                            'latitude'=> '19.076090',
                            'longitude' => '72.877426'
                        ]
                    ]
                ],
                'destination' => [
                    'location' => [
                        'latLng' => [
                            'latitude'=> '28.613939',
                            'longitude' => '77.209023'
                        ]
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
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
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
