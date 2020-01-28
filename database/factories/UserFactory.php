<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\User::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'position'  => $faker->name,
        'email' => $faker->unique()->email,
        'password' => \Illuminate\Support\Facades\Hash::make($faker->password),
        'role_id' => rand(1, 2),
        'api_token' => $faker->randomKey()
    ];
});
