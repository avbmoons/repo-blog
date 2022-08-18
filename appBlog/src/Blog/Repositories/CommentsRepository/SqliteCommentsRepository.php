<?php

namespace Elena\AppBlog\Blog\Repositories\CommentsRepository;

use Elena\AppBlog\Blog\Exceptions\InvalidArgumentException;
use Elena\AppBlog\Blog\Exceptions\UserNotFoundException;
use Elena\AppBlog\Blog\Exceptions\CommentNotFoundException;
use Elena\AppBlog\Blog\Comment;
use Elena\AppBlog\Blog\Post;
use Elena\AppBlog\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Elena\AppBlog\Blog\Repositories\UsersRepository\SqliteUsersRepository;
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

    /**
     * @throws CommentNotFouhdException
     * @throws InvalidArgumentException|UserNotFoundException
     */
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
                "Cannot find comment: $uuid"
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
            ':post_uuid' => $comment->getPost()->getUuid(),
            ':author_uuid' => $comment->getUser()->uuid(),
            ':text' => $comment->getText(),
        ]);
    }

    /**
     * @throws CommentNotFoundException
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     */
    private function getComment(\PDOStatement $statement, string $commentUuid): Comment
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            throw new CommentNotFoundException(
                "Cannot find comment: $commentUuid"
            );
        }

        $postRepository = new SqlitePostsRepository($this->connection);
        $post = $postRepository->get(new UUID($result['uuid']));

        $userRepository = new SqliteUsersRepository($this->connection);
        $user = $userRepository->get(new UUID($result['user']));

        return new Comment(
            new UUID($result['uuid']),
            $post,
            $user,
            $result['text']
        );
    }
}
