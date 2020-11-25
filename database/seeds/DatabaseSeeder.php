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
              ClientsTableSeeder::class,
              CurrenciesTableSeeder::class,
              LanguageTableSeeder::class,
           // TeamSeeder::class,
              //UsersTableDataSeeder::class,
              //TagSeeder::class
              
             ]);
    }
}