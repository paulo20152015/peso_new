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
             AdminsTableSeeder::class,
             Company_addressesTableSeeder::class,
             TownsTableSeeder::class,
             Job_postsTableSeeder::class,
             SpecializationsTableSeeder::class,
             Job_specializationsTableSeeder::class,
             ]);
    }
}
