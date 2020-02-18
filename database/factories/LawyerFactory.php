<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Lawyer;
use Faker\Generator as Faker;

$factory->define(Lawyer::class, function (Faker $faker) {
    return [
        'title_id' => $faker->numberBetween(1,6),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'image' => "default.png",
        'rank_id' => $faker->numberBetween(1,5),
        'email' => $faker->unique()->companyEmail,
        'phone' => $faker->unique()->e164PhoneNumber,
        'addon_rate' => $faker->randomFloat(2,0,10),
    ];
});
