<?php

use Elena\AppBlog\Blog\Commands\Arguments;
use Elena\AppBlog\Blog\Commands\CreateUserCommand;
use Elena\AppBlog\Blog\Comment;
use Elena\AppBlog\Blog\Post;
use Elena\AppBlog\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Elena\AppBlog\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Elena\AppBlog\Person\Name;
use Elena\AppBlog\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\UUID;


require_once __DIR__ . '/vendor/autoload.php';

try {
    // Создаем объект подключения к БД
    $connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

    $faker = Faker\Factory::create('ru_RU');

    $postRepository = new SqlitePostsRepository($connection);

    //$post = $postRepository->get(new UUID('0a120dd1-f8d2-424a-8987-9f97f0ec8d86'));

    $post = $postRepository->get(new UUID('8c4db23d-931d-4dd0-9c33-5eec4ee3cc58'));

    echo $post->getTitle() . PHP_EOL;
    echo ($post);
    echo $post->getUser();

    // $post = new Post(
    //     UUID::random(),
    //     UUID::random(),
    //     $faker->realText(rand(20, 30)),
    //     $faker->realText(rand(200, 280))
    // );

    // $postRepository->save($post);

    //Создаём объект репозитория
    //$usersRepository = new SqliteUsersRepository($connection);
    // $usersRepository = new InMemoryUsersRepository();

    // $command = new CreateUserCommand($usersRepository);


    //  $command->handle(Arguments::fromArgv($argv));

    // $user = $usersRepository->getByUsername('ivan');
    // print $user;
    // $usersRepository->save(new User(UUID::random(), 'admin', new Name('Ivan', 'Nikitin')));
    // echo $usersRepository->getByUsername('admin');
    //$usersRepository->save(new User(2, new Name('Anna', 'Mokova')));

} catch (Exception $exception) {
    $exception->getMessage();
}
