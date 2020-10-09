<?php

use Illuminate\Database\Seeder;
use App\Model\Plan;
class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::create(
            ['name'=>'Basic Trial','amount'=>0,'description'=>'Description of the basic plan']  
        );
        Plan::create(
            ['name'=>'Plan2','amount'=>100,'description'=>'Description of the  plan2']
        );
        Plan::create(
            ['name'=>'Basic Trial','amount'=>0,'description'=>'Description of the basic plan']
        );
    }
}
