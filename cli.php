<?php

use devavi\leveltwo\User\User;
use devavi\leveltwo\Blog\{Post, Comment};

include __DIR__ . "/vendor/autoload.php";

$faker = Faker\Factory::create('ru_RU');

if ($argv[1] == "user"){
    try {
        echo $user1 = new User(
            $faker->randomNumber(2, false),
            $faker->name(),
            $faker->email());

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

if ($argv[1] == "post"){
    try {
        echo $post1 = new Post(
            $faker->randomNumber(3, false),
            $faker->randomNumber(2, false),
            $faker->sentence(5),
            $faker->text(100));

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

if ($argv[1] == "comment"){
    try {
        echo $Comment1 = new Comment(
            $faker->randomNumber(2, false),
            $faker->randomNumber(2, false),
            $faker->randomNumber(2, false),
            $faker->text(100));

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}