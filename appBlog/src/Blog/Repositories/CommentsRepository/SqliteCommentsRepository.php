<?php

namespace Elena\AppBlog\Blog\Repositories\CommentsRepository;

use Elena\AppBlog\Blog\Exceptions\InvalidArgumentException;
use Elena\AppBlog\Blog\Exceptions\UserNotFoundException;
use Elena\AppBlog\Blog\Exceptions\CommentNotFoundException;
use Elena\AppBlog\Blog\Comment;
//use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\UUID;
//use Elena\AppBlog\Person\Name;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare('SELECT * FROM comments WHERE uuid = :uuid');
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        // Бросаем исключение, если comment не найден
        if ($result === false) {
            throw new CommentNotFoundException(
                "Cannot get comment: $uuid"
            );
        }

        return $this->getComment($statement, $uuid);
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, post_uuid, author_uuid, text) VALUES (:uuid, :post_uuid, :author_uuid, :text)'
        );

        // Выполняем запрос с конкретными значениями
        $statement->execute([
            ':uuid' => $comment->getUuid(),
            ':post_uuid' => $comment->getPostUuid(),
            ':author_uuid' => $comment->getAuthorUuid(),
            ':text' => $comment->getText(),
        ]);
    }

    private function getComment(\PDOStatement $statement, string $commentUuid): Comment
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            throw new CommentNotFoundException(
                "Cannot find post: $commentUuid"
            );
        }
        // Создаём объект пользователя с полем username
        return new Comment(
            new UUID($result['uuid']),
            new UUID($result['post_uuid']),
            new UUID($result['author_uuid']),
            $result['text']
        );
    }
}
