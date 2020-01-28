<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Table::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'columns' => serialize(['test1', 'test2']),
        'creator_id' => 1
    ];
});
