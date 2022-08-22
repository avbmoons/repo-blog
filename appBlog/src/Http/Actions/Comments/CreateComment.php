<?php

namespace Elena\AppBlog\Http\Actions\Comments;

use Elena\AppBlog\Blog\Comment;
use Elena\AppBlog\Blog\Exceptions\UserNotFoundException;
use Elena\AppBlog\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Elena\AppBlog\Blog\UUID;
use Elena\AppBlog\Http\ErrorResponse;
use Elena\AppBlog\Http\HttpException;
use Elena\AppBlog\Http\Request;
use Elena\AppBlog\Http\Response;
use Elena\AppBlog\Http\SuccessfulResponse;
use InvalidArgumentException;

class CreateComment
{
    // Внедряем репозиторий статей
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        //private UsersRepositoryInterface $usersRepository,
    ) {
    }

    public function handle(Request $request): Response
    {
        // Пытаемся создать UUID статьи из данных запроса
        try {
            $postUuid = new UUID($request->jsonBodyField('post_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        // Пытаемся найти статью в репозитории
        try {
            $this->postsRepository->get($postUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        // Генерируем UUID для нового комментария
        $newCommentUuid = UUID::random();

        try {
            // Пытаемся создать объект комментария
            // из данных запроса

            $comment = new Comment(
                $newCommentUuid,
                $postUuid,
                $request->jsonBodyField('author_uuid'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        // Сохраняем новый комментарий в репозитории
        $this->commentsRepository->save($comment);
        // Возвращаем успешный ответ,
        // содержащий UUID нового комментария
        return new SuccessfulResponse([
            'uuid' => (string)$newCommentUuid,
        ]);
    }
}
