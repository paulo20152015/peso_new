<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Specialization;
use Faker\Generator as Faker;
$factory->define(Specialization::class, function (Faker $faker) {
    return [
        //
        'specialization'=>$faker->word,
    ];
});
