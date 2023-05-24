<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use GuzzleHttp\Client;
use App\Model\ClientPreference;
use App\Model\Warehouse;
use Log;
use Illuminate\Support\Facades\DB;
use Unifonic;

trait DispatcherRouteAllocation
{

    public function findNearestWarehouse($data, $ids,$is_last = null)
    {

        $query = "
            SELECT
                id,
                latitude,
                longitude,
                address,
                ROUND(
                    6371 * ACOS(
                        COS(RADIANS(?))
                        * COS(RADIANS(latitude))
                        * COS(RADIANS(?)- RADIANS(longitude))
                        + SIN(RADIANS(?))
                        * SIN(RADIANS(latitude))
                    ),4
                ) AS distance
            FROM
                warehouses
            WHERE
                latitude IS NOT NULL
                AND longitude IS NOT NULL";
    
        $params = [];
    
        if (is_array($data)) {
            $params = [$data['latitude'], $data['longitude'], $data['latitude']];
        } else {
            $params = [$data->latitude, $data->longitude, $data->latitude];
        }
    
       
        $query .= "
            ORDER BY
            distance ASC;
        ";
       


        $result = DB::select($query, $params);
      
       if($is_last)
       {
        $result = DB::selectOne($query, $params);

         return $result;
       }
        
       $filteredResults = [];
       $found = false;
   
       foreach ($result as $warehouse) {
           $filteredResults[] = $warehouse;
           if ($warehouse->id == $ids) {
               $found = true;
               break;
           }
       }
       return $filteredResults;

       
    
    }
    
   
}
