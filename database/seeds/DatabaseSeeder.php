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
              //PassportSeeder::class,
           // TeamSeeder::class,
              //UsersTableDataSeeder::class,
              //TagSeeder::class
              
             ]);
    }
}