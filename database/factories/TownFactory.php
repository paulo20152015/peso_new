<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Town;
use Faker\Generator as Faker;

$factory->define(Town::class, function (Faker $faker) {
    return [
        'town'=>$faker->cityPrefix,
        'city_id'=>function () {
            return factory(App\City::class)->create()->id;
        },
    ];
});
