<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Pokemon;
use App\Task;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Task::class, static function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->sentence,
        'theory' => $faker->sentence,
        'answerTemplate' => $faker->sentence,
        'successCriteria' => $faker->sentence,
    ];
});
