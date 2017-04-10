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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Concert::class, function (Faker\Generator $faker) {
    return [
        'title' =>  'Example Title',
        'subtitle' => 'Example Title',
        'date' => \Carbon\Carbon::parse('+2 weels'),
        'ticket_price' => 2000,
        'venue' => 'The Example Theatre',
        'venus_address' => '123 example lane',
        'city' => 'City',
        'state' => 'State',
        'zip' => '12354',
        'additional_information' => 'Example show information',
    ];
});

$factory->state(App\Concert::class, 'published' ,function (Faker\Generator $faker) {
    return [
        'published_at' => \Carbon\Carbon::parse('-1 week'),
    ];
});

$factory->state(App\Concert::class, 'unpublished' ,function (Faker\Generator $faker) {
    return [
        'published_at' => null,
    ];
});
