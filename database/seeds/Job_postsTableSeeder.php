<?php

use Illuminate\Database\Seeder;

class Job_postsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $job_posts = factory(App\Job_post::class, 150)->create();
    }
}
