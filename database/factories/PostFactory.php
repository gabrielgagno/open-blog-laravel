<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->words(6, true),
        'body' =>  $faker->htmlLorem(2,3),
        'tags' => implode(',', $faker->words(3, false)),
    ];
});

$factory->state(App\Post::class, 'published', [
    'status' => 'published',
]);

$factory->state(App\Post::class, 'draft', [
    'status' => 'draft',
]);

$factory->state(App\Post::class, 'archived', [
    'status' => 'archived',
]);