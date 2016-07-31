<?php

use App\User;
use App\Recipe;

$factory->define(User::class, function (Faker\Generator $faker) {
    $name = $faker->name;

    return [
        'name' => $name,
        'username' => strtolower(preg_replace('/\W+/', '', substr($name, 0, rand(6, 8)))),
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Recipe::class, function (Faker\Generator $faker) {
    $title = $faker->sentence;

    return [
        'title' => $title,
        'slug' => str_slug($title),
        'body' => $faker->paragraphs(3, true),
        'image' => $faker->imageUrl(640, 480),
    ];
});
