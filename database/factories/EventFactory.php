<?php

use Baytek\Laravel\Content\Types\Events\Models\Category;
use Baytek\Laravel\Content\Types\Events\Models\Event;
/**
 * Events Categories
 */
$factory->define(Category::class, function (Faker\Generator $faker) {

    $title = ucwords(implode(' ', $faker->unique()->words(rand(1,2))));

    return [
        'key' => str_slug($title),
        'title' => $title,
        'content' => null,
        'status' => Category::APPROVED,
        'language' => App::getLocale(),
    ];
});

/**
 * Events Items
 */
$factory->define(Event::class, function (Faker\Generator $faker) {

    $title = $faker->sentence();

    return [
        'key' => str_slug($title),
        'title' => $title,
        'content' => implode('<br/><br/>', $faker->paragraphs(rand(1, 2))),
        'status' => Event::APPROVED,
        'language' => App::getLocale(),
    ];
});
