<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Job_specialization;
use Faker\Generator as Faker;

$factory->define(Job_specialization::class, function (Faker $faker) {
    return [
        //
        'job_post_id'=>rand(1,150),
        'specialization_id'=>rand(1,10),
    ];
});
