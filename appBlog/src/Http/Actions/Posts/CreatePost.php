<?php

namespace Elena\AppBlog\Http\Actions\Posts;

use Elena\AppBlog\Blog\Exceptions\InvalidArgumentException;
use Elena\AppBlog\Http\Actions\ActionInterface;
use Elena\AppBlog\Http\ErrorResponse;
use Elena\AppBlog\Http\HttpException;
use Elena\AppBlog\Http\Request;
use Elena\AppBlog\Http\Response;
use Elena\AppBlog\Http\SuccessfulResponse;
use Elena\AppBlog\Blog\Post;
use Elena\AppBlog\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Elena\AppBlog\Blog\Exceptions\UserNotFoundException;
use Elena\AppBlog\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\UUID;


class CreatePost implements ActionInterface
{
    // Внедряем репозитории статей и пользователей
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private UsersRepositoryInterface $usersRepository,
    ) {
    }

    public function handle(Request $request): Response
    {
        // Пытаемся создать UUID пользователя из данных запроса
        try {
            $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        // Пытаемся найти пользователя в репозитории
        try {
            $this->usersRepository->get($authorUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        // Генерируем UUID для новой статьи
        $newPostUuid = UUID::random();

        try {
            // Пытаемся создать объект статьи
            // из данных запроса

            $post = new Post(
                $newPostUuid,
                $authorUuid,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        // Сохраняем новую статью в репозитории
        $this->postsRepository->save($post);
        // Возвращаем успешный ответ,
        // содержащий UUID новой статьи
        return new SuccessfulResponse([
            'uuid' => (string)$newPostUuid,
        ]);
    }
}
