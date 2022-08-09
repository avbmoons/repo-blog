<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/functions.php';

use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\Post;
use Elena\AppBlog\Blog\Comment;

$faker = Faker\Factory::create('ru_RU');

$message = 'Введите один из аргументов:' . PHP_EOL . 'user' . PHP_EOL . 'comment' . PHP_EOL . 'post';

if (empty($argv[1])) {
    die($message);
} else {
    $inputData = $argv[1];
}

switch ($inputData) {
    case 'user':
        $id = (int)$faker->uuid();
        $name = $faker->firstName();
        $surname = $faker->lastName();
        echo new User($id, $name, $surname);
        break;

    case 'post':
        $id = (int)$faker->uuid();
        $heading = $faker->realText(rand(50, 80));
        $text = $faker->realText(rand(200, 280));
        echo new Post($id, createUser($faker), $heading, $text);
        break;

    case 'comment':
        $id = (int)$faker->uuid();
        $text = $faker->realText(rand(200, 280));
        echo new User($id, createUser($faker), createPost($faker), $text);
        break;
    default:
        echo $message;
}
