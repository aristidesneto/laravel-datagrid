<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'category_id' => $faker->numberBetween(1, 8),
        'title'       => $faker->name,
        'slug'        => str_slug($faker->name),
        'body'        => $faker->text,
        'image'       => 'https://picsum.photos/800/450/?image=' . $faker->numberBetween(1, 100)
    ];
});
