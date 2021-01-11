<?php

use Illuminate\Database\Seeder;

class createPricingRule extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('price_rules')->delete();
 
        $type = array(
            array(
                'id'                               => 1,
                'name'                             => 'defult',
                'start_date_time'                  => '2021-01-01 00:00:00',
                'end_date_time'                    => '2030-01-29 00:00:00',
                'is_default'                       => 1,
                'geo_id'                           => 1,
                // 'team_id'                          => ,
                // 'team_tag_id'                      => '',
                // 'driver_tag_id'                    => '',
                'base_price'                       => 10,
                'base_duration'                    => 50.00,
                'base_distance'                    => 20.00,
                'base_waiting'                     => 10,
                'duration_price'                   => 50.00,
                'waiting_price'                    => 20.00,
                'distance_fee'                     => 30.00,
                'cancel_fee'                       => 20.00,
                'agent_commission_percentage'      => 20,
                'agent_commission_fixed'           => 30,
                'freelancer_commission_percentage' => 20,
                'freelancer_commission_fixed'      => 20,
            )
        );
        DB::table('price_rules')->insert($type);
    }
}
