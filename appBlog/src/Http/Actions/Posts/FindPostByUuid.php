<?php

namespace Elena\AppBlog\Http\Actions\Posts;

use Elena\AppBlog\Http\Actions\ActionInterface;
use Elena\AppBlog\Http\ErrorResponse;
use Elena\AppBlog\Http\HttpException;
use Elena\AppBlog\Http\Request;
use Elena\AppBlog\Http\Response;
use Elena\AppBlog\Http\SuccessfulResponse;
use Elena\AppBlog\Blog\Exceptions\PostNotFoundException;
use Elena\AppBlog\Blog\Repositories\PostsRepository\PostsRepositoryInterface;

// Класс реализует контракт действия
class FindPostByUuid implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    ) {
    }

    // Функция, описанная в контракте
    public function handle(Request $request): Response
    {
        try {
            // Пытаемся получить искомый uuid статьи из запроса
            $postUuid = $request->query('uuid');
        } catch (HttpException $e) {
            // Если в запросе нет параметра postUuid -
            // возвращаем неуспешный ответ,
            // сообщение об ошибке берём из описания исключения
            return new ErrorResponse($e->getMessage());
        }
        try {
            // Пытаемся найти статью в репозитории
            $post = $this->postsRepository->get($postUuid);
        } catch (PostNotFoundException $e) {
            // Если статья не найдена -
            // возвращаем неуспешный ответ
            return new ErrorResponse($e->getMessage());
        }
        // Возвращаем успешный ответ
        return new SuccessfulResponse([
            'post' => $post->getUuid(),
            'text' => $post->getTitle() . ' ' . $post->getText(),
        ]);
    }
}
