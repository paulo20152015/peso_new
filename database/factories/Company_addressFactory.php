<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;
use App\Company_detail;
use App\Company_address;
$factory->define(Company_address::class, function (Faker $faker) {
    return [
        //
        'city' => $faker->city,
        'town' => $faker->city,
        'street'=>$faker->streetAddress,
        'company_detail_id'=>function(){
            return factory(Company_detail::class)->create()->id;
        }
    ];
});
