<?php

use Illuminate\Database\Seeder;

class CreateCustomizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('agents')->delete();
 
        $type = array(
            array(
                'theme'=> 'light',
                'distance_unit'=> 'metric',
                'currency_id'=> 147,
                'language_id'=> 1,
                'acknowledgement_type' => 'acceptreject',
                'make_model'      => '2021',
                'plate_number'    => 'pb-yb-2000',
                'phone_number'    => '9638527410',
                'color'           => 'red',
            )
        );
        DB::table('agents')->insert($type);
    }
}
