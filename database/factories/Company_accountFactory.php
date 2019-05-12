<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;
use App\Company_account;
$factory->define(Company_account::class, function (Faker $faker) {
    return [
        'username'=>$faker->unique()->userName,
        'password'=>Hash::make('12345678'),
        'number'=>'0912838783'
    ];
});
