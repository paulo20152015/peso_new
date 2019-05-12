<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;
use App\Company_detail;
use App\Company_account;
$factory->define(Company_detail::class, function (Faker $faker) {
    return [
        //
        'name' => $faker->unique()->company,
        'email' => $faker->unique()->companyEmail,
        'contact_number'=>$faker->phoneNumber,
        'company_size'=>$faker->numberBetween($min = 0, $max = 10).' to '.$faker->numberBetween($min = 50, $max = 1000),
        'land_line'=>$faker->tollFreePhoneNumber,
        'overview'=>$faker->text($maxNbChars = 400),
        'website'=>$faker->url,
        'company_account_id'=>function(){
            return factory(Company_account::class)->create()->id;
        }

    ];
});
