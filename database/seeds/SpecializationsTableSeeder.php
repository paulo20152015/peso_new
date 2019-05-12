<?php

use Illuminate\Database\Seeder;

class SpecializationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $specializations = factory(App\Specialization::class,10)->create();
    }
}
