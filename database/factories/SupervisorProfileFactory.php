<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\SupervisorProfile;
use App\Models\Teacher;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(SupervisorProfile::class, function (Faker $faker) {
    return [
        'supervisor_type' => $type = $faker->randomElement([Teacher::class, User::class]),
        'supervisor_id' => function () use ($type) {
            return factory($type)->create()->id;
        },
    ];
});
