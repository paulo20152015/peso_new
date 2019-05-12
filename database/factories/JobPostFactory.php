<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Job_post;
use Faker\Generator as Faker;

$factory->define(Job_post::class, function (Faker $faker) {
    return [
        'title'=>$faker->sentence($nbWords = 3, $variableNbWords = true),
        'full_address'=>$faker->address,
        'work_experience'=>(rand(1,10)."Years"),
        'job_description'=>$faker->sentences($nb = rand(1,10), $asText = true), 
        'job_requirements'=>$faker->sentence($nb = rand(1,10), $asText = true),
        'town_id'=>rand(1,10),
        'company_account_id'=>rand(1,40), 
    ];
});
