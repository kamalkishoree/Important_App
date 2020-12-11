<?php

use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('task_types')->delete();
 
        $type = array(
            array(
                'id' => 1,
                'name' => 'onfoot'
            ),
            array(
                'id' => 2,
                'name' => 'bycycle'
            ),
            array(
                'id' => 3,
                'name' => 'motorbike'
            )
            array(
                'id' => 3,
                'name' => 'car'
            )
            array(
                'id' => 3,
                'name' => 'truck'
            )
        );
        DB::table('task_types')->insert($type);
    }
}