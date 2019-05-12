<?php

use Illuminate\Database\Seeder;

class Job_specializationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $job_specializations = factory(App\Job_specialization::class,300)->create();
    }
}
