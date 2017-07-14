<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

//factory(App\User::class, 'admin', 10)->create();
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

//数据填充  Faker 是一个第三方库 GitHub上有
$factory->define(App\Post::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence($nbWords = 6, $variableNbWords = true) ,
        'content' => $faker->paragraph($nbSentences = 10, $variableNbSentences = true),
    ];
});

$factory->define(\App\Phone::class, function (Faker\Generator $faker) {
    return [
        'num' => $faker->sentence($nbWords = 6, $variableNbWords = true) ,
        'user_id' =>  function () {
            return factory(App\User::class)->create()->id;
        } ,
    ];
});

$factory->define(\App\Comment::class, function (Faker\Generator $faker) {
    return [
        'content' => $faker->sentence($nbWords = 6, $variableNbWords = true) ,
        'user_id' =>  function () {
            return factory(App\User::class)->create()->id;
        } ,
        'post_id' => function () {
            return factory(App\Post::class)->create()->id;
        } ,
    ];
});

