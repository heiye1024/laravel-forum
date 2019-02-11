<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Topic::class, function (Faker $faker) {

    $sentence = $faker->sentence();

    // 隨機取一個月以內的時間
    $updated_at = $faker->dateTimeThisMonth();

    // 建立時間需要比更改時間更早
    $created_at = $faker->dateTimeThisMonth($updated_at);

    return [
        'title' => $sentence,
        'body' => $faker->text(),
        'excerpt' => $sentence,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
