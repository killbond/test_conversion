<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Customer::class, function (Faker\Generator $faker) {
    $date = $faker->dateTimeBetween('-1 year');
    return [
        'name' => $faker->name,
        'surname' => $faker->lastName,
        'phone' => $faker->phoneNumber,
        'status_id' => $faker->randomElement([1, 2, 3, 4]),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
