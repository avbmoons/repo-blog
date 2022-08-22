<?php

namespace Elena\AppBlog\Http\Actions\Comments;

use Elena\AppBlog\Blog\Exceptions\CommentNotFoundException;
use Elena\AppBlog\Blog\Exceptions\HttpException;
use Elena\AppBlog\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;
use Elena\AppBlog\Http\Actions\ActionInterface;
use Elena\AppBlog\Http\ErrorResponse;
use Elena\AppBlog\Http\Request;
use Elena\AppBlog\Http\Response;
use Elena\AppBlog\Http\SuccessfulResponse;

class FindCommentByUuid implements ActionInterface
{
    public function __construct(
        private CommentsRepositoryInterface $commentsRepository
    ) {
    }

    // Функция, описанная в контракте
    public function handle(Request $request): Response
    {
        try {
            // Пытаемся получить искомый uuid комментария из запроса
            $commentUuid = $request->query('uuid');
        } catch (HttpException $e) {
            // Если в запросе нет параметра commentUuid -
            // возвращаем неуспешный ответ,
            // сообщение об ошибке берём из описания исключения
            return new ErrorResponse($e->getMessage());
        }
        try {
            // Пытаемся найти комментарий в репозитории
            $comment = $this->commentsRepository->get($commentUuid);
        } catch (CommentNotFoundException $e) {
            // Если комментарий не найден -
            // возвращаем неуспешный ответ
            return new ErrorResponse($e->getMessage());
        }
        // Возвращаем успешный ответ
        return new SuccessfulResponse([
            'comment' => $comment->getUuid(),
            'text' => $comment->getText(),
        ]);
    }
}
