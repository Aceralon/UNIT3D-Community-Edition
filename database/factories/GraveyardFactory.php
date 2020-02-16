<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Graveyard::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'torrent_id' => function () {
            return factory(App\Models\Torrent::class)->create()->id;
        },
        'seedtime' => $faker->randomNumber(),
        'rewarded' => $faker->boolean,
    ];
});
