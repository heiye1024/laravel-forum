<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Reply::class, function (Faker $faker) {

    // 隨機取一個月內的時間
    $time = $faker->dateTimeThisMonth();

    return [
        'content' => $faker->sentence(),
        'created_at' => $time,
        'updated_at' => $time,
    ];
});
