<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Illuminate\Support\Str;
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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => empty(\App\User::all()->toArray()) ? 'adnan' : $faker->name,
        'email' => empty(\App\User::all()->toArray()) ? 'adnan@test.com' : $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'remember_token' => Str::random(10),
        'confirm' => now()
    ];
});

$factory->state(User::class, 'unconfirmed', function (Faker $faker) {
    return [
        'confirm' => null
    ];
});

$factory->state(User::class, 'non-admin', function (Faker $faker) {
    return [
        'name' => 'user'
    ];
});

$factory->define(\App\Thread::class, function (Faker $faker) {
    $title = $faker->sentence(1, 3);

    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'channel_id' => function () {
            return factory(\App\Channel::class)->create()->id;
        },
        'body' => $faker->text,
        'title' => $title,
        'locked' => null
    ];
});

$factory->state(\App\Thread::class, 'locked', function (Faker $faker) {
    return [
        'locked' => now()
    ];
});

$factory->define(\App\Channel::class, function (Faker $faker) {
    return [
        'slug' => $faker->slug,
        'name' => $faker->name
    ];
});

$factory->define(\App\Reply::class, function (Faker $faker) {
    return [
        'thread_id' => function () {
            return factory(\App\Thread::class)->create()->id;
        },
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'body' => $faker->text
    ];
});

$factory->define(\App\Role::class, function (Faker $faker) {
    return [
        'slug' => empty(\App\Role::all()) ? 'admin' : $faker->slug(1),
        'title' => empty(\App\Role::all()) ? 'Admin' : $faker->title(1)
    ];
});

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function (Faker $faker) {
    return [
        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type' => 'App\Notifications\ThreadWasUpdated',
        'notifiable_type' => 'App\User',
        'notifiable_id' => function () { return auth()->id() ?: factory('App\User')->create()->id; },
        'data' => '{"message":"temporary notification"}',
    ];
});
