<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;
use App\City;
$factory->define(City::class, function (Faker $faker) {
    return [
        //
        'city'=>$faker->city,
    ];
});
