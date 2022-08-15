<?php

use Elena\AppBlog\Blog\Commands\Arguments;
use Elena\AppBlog\Blog\Commands\CreateUserCommand;
use Elena\AppBlog\Blog\Comment;
use Elena\AppBlog\Blog\Post;
use Elena\AppBlog\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Elena\AppBlog\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Elena\AppBlog\Person\Name;
use Elena\AppBlog\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Elena\AppBlog\Blog\UUID;


require_once __DIR__ . '/vendor/autoload.php';
// Создаем объект подключения к БД
//$userRepository = new SqliteUsersRepository(new PDO('sqlite:' . __DIR__ . '/blog.sqlite'));

try {   //

    $connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

    $faker = Faker\Factory::create('ru_RU');

    //$postRepository = new SqlitePostsRepository($connection);
    $commentRepository = new SqliteCommentsRepository($connection);
    //$commentRepository = new SqliteCommentsRepository(new PDO('sqlite:' . __DIR__ . '/blog.sqlite'));


    // Тут проверка извлечения поста по его uuid
    //echo $postRepository->get(new UUID('8c4db23d-931d-4dd0-9c33-5eec4ee3cc58'));

    // //
    // //Создаем новый пост и сохраняем его в БД

    // $post = new Post(
    //     UUID::random(),
    //     UUID::random(),
    //     $faker->realText(rand(20, 30)),
    //     $faker->realText(rand(100, 200))
    // );

    // $postRepository->save($post);

    // echo $post;
    // //

    //
    //Создаем новый коммент и сохраняем его в БД

    $comment = new Comment(
        UUID::random(),
        UUID::random(),
        UUID::random(),
        $faker->realText(rand(100, 200))
    );

    $commentRepository->save($comment);

    echo $comment;
    //

    //$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

    // Создаем объект репозитория (пользователя)
    //$userRepository = new SqliteUsersRepository($connection);

    // Создаем экземпляр объекта в репозитории (пользователя)
    // $command = new CreateUserCommand($userRepository); // снять //

    //try { //

    // $command->handle(Arguments::fromArgv($argv));    // снять //
    // $user = $userRepository->getByUsername('ivan');  // снять //
    // print $user; // снять //
} catch (Exception $exception) {
    $exception->getMessage();
}
