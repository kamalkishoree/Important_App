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
trait DispatcherRouteAllocation{


    public function dispatcherAutoAllocation($customer, $vendor)
{
    $user_lat = $customer->latitude;
    $user_long = $customer->longitude;

    $warehouse_bangalore = $customer;
    $distance_to_product = round($this->getDistance($vendor[1]['latitude'], $vendor[1]['longitude'], $user_lat, $user_long));

    $ids = [$warehouse_bangalore->id];
    $nearest_warehouse = $warehouse_bangalore;

    while ($distance_to_product > 50) {
        $nearest_warehouse = $this->findNearestWarehouse($nearest_warehouse, $ids, $distance_to_product);
        if (empty($nearest_warehouse)) {
            break;
        }
        $distance_to_product = $this->getDistance($vendor[1]['latitude'], $vendor[1]['longitude'], $nearest_warehouse->latitude, $nearest_warehouse->longitude);

        $ids[] = $nearest_warehouse->id;
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $query = "SELECT id, name, address FROM warehouses WHERE id IN ($placeholders) ORDER BY FIELD(id, $placeholders)";
    $warehouses = DB::select($query, array_merge($ids, $ids));

    $final_route = [];
    foreach ($warehouses as $warehouse) {
        $final_route[] = [
            'id' => $warehouse->id,
            'warehouse_name' => $warehouse->name,
            'address' => $warehouse->address,
        ];
    }

    return $final_route;
}

public function findNearestWarehouse($data, $ids, $dist)
{
    
    $client = ClientPreference::where('id',1)->first();
    $placeholders = rtrim(str_repeat('?,', count($ids)), ',');
    $query = "SELECT id, latitude, longitude FROM warehouses WHERE id NOT IN ($placeholders) AND latitude IS NOT NULL AND longitude IS NOT NULL";
    if(($client->is_dispatcher_allocation == 1) && ($client->use_large_hub == 1))
    {
        $query .= " AND type = 1";
    } 

    $warehouses = DB::select($query, $ids);

    $distances = [];
    foreach ($warehouses as $warehouse) {
        $distance = $this->getDistance($data->latitude, $data->longitude, $warehouse->latitude, $warehouse->longitude);

        if ($distance > 50) {
            $distances[$warehouse->id] = $distance;
        }
    }

    asort($distances);

    $nearestWarehouseId = key($distances);

    $query = "SELECT id, name, address ,latitude,longitude FROM warehouses WHERE id = ?";
    $nearestWarehouse = DB::selectOne($query, [$nearestWarehouseId]);

    return $nearestWarehouse;
}

public function getDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371; // in kilometers
    $deltaLat = deg2rad($lat2 - $lat1);
    $deltaLon = deg2rad($lon2 - $lon1);
    $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($deltaLon / 2) * sin($deltaLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earthRadius * $c;
    return $distance; // in kilometers
}



}