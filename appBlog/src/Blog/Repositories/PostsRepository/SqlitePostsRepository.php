<?php

namespace Elena\AppBlog\Blog\Repositories\PostsRepository;

//use Elena\AppBlog\Blog\Exceptions\InvalidArgumentException;
//use Elena\AppBlog\Blog\Exceptions\UserNotFoundException;
use Elena\AppBlog\Blog\Exceptions\PostNotFoundException;
use Elena\AppBlog\Blog\Post;
//use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\UUID;
//use Elena\AppBlog\Person\Name;

class SqlitePostsRepository implements PostsRepositoryInterface
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare('SELECT * FROM posts WHERE uuid = :uuid');
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        // Бросаем исключение, если пользователь не найден
        if ($result === false) {
            throw new PostNotFoundException(
                "Cannot get post: $uuid"
            );
        }

        return $this->getPost($statement, $uuid);
    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text) VALUES (:uuid, :author_uuid, :title, :text)'
        );

        // Выполняем запрос с конкретными значениями
        $statement->execute([
            ':uuid' => $post->getUuid(),
            ':author_uuid' => $post->getAuthorUuid(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText(),
        ]);
    }

    private function getPost(\PDOStatement $statement, string $postUuid): Post
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            throw new PostNotFoundException(
                "Cannot find post: $postUuid"
            );
        }
        // Создаём объект пользователя с полем username
        return new Post(
            new UUID($result['uuid']),
            new UUID($result['author_uuid']),
            $result['title'],
            $result['text']
        );
    }
}
