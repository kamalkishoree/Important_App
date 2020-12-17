<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
              CurrenciesTableSeeder::class,
              CountriesTableSeeder::class,
              ClientsTableSeeder::class,
              LanguageTableSeeder::class,
              VehicleTypeTableSeeder::class,
              TaskTypeTableSeeder::class,
              NotificationSeeder::class,
              UsersTableDataSeeder::class,
              PlanSeeder::class,
              PricePriority::class,
              
             ]);
    }
}