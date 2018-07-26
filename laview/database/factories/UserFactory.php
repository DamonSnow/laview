<?php

use Faker\Generator as Faker;

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

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'avatar' => $faker->imageUrl('https://file.iviewui.com/dist/a0e88e83800f138b94d2414621bd9704.png'),
        'job_number' => $faker->unique()->numerify('#####'),
        'phone' => substr($faker->phoneNumber,0,11),
        'active' => 1,
        'password' => bcrypt('123456'), // secret
        'remember_token' => str_random(10),
        'created_at' => $faker->dateTimeThisMonth()
    ];
});
