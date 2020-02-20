<?php

/** @var Factory $factory */

use App\Timesheet;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Timesheet::class, function (Faker $faker) {
    $start_time = Carbon::today()->addHours($faker->numberBetween(4, 7));

    return [
        'lawyer_id' => $faker->numberBetween(1,10),
        'client_id' => $faker->numberBetween(1,20),
        'start_time' => $start_time->toDateTimeString(),
        'end_time' => $start_time->addHours($faker->numberBetween(3,12))->toDateTimeString(),
   ];
});
