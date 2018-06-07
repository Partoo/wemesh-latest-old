<?php

$factory->define(Stario\Wesite\Models\Post::class, function () {
    $faker = Faker\Factory::create('zh_CN');
    $cats = Stario\Wesite\Models\Category::where('id', '>', 0)->pluck('id')->toArray();
    $type = $faker->randomElement(['post', 'page']);
    if ($type === 'page') {
        $categoryId = 1;
    } else {
        $categoryId = $faker->randomElement($cats);
    }
    return [
        'category_id' => $categoryId,
        'title' => $faker->sentence(),
        'content' => $faker->realText(),
        'status' => $faker->randomElement([0, 1]),
        'type' => $type,
        'author_id' => $faker->randomElement([1, 2]),
    ];
});
