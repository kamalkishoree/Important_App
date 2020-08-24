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
        Plan::createMany([
            ['name'=>'Basic Trial','amount'=>0,'description'=>'Description of the basic plan'],
            ['name'=>'Plan2','amount'=>100,'description'=>'Description of the  plan2'],
            ['name'=>'Paln3','amount'=>1000,'description'=>'Description of the basic plan3'],
        ]);
    }
}
