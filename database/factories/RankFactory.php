<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Rank;
use Faker\Generator as Faker;

$factory->define(Rank::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->jobTitle,
        'rate' => $faker->randomFloat(2,100,10000),
    ];
});
