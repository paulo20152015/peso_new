<?php

use Faker\Generator as Faker;
use App\Admin;
use Illuminate\Support\Facades\Hash;

$factory->define(Admin::class, function (Faker $faker) {
    return [
        'username'=>$faker->unique()->userName,
        'password'=>Hash::make('12345678'),
        'is_archive'=>false,
        'level'=>$faker->numberBetween($min = 1, $max = 2)
    ];
});
