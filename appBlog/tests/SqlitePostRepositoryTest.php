<?php

use Elena\AppBlog\Blog\Exceptions\UserNotFoundException;
use Elena\AppBlog\Blog\Exceptions\PostNotFoundException;
use Elena\AppBlog\Blog\Post;
use Elena\AppBlog\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\UUID;
use Elena\AppBlog\Person\Name;
use PHPUnit\Framework\TestCase;
use Elena\AppBlog\Blog\Exceptions\InvalidArgumentException;

class SqlitePostRepositoryTest extends TestCase
{
    /**
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    public function testItThrowsAnExceptionWhenPostNotFound(): void
    {
        $connectionMock = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);
        $connectionMock->method('prepare')->willReturn($statementStub);

        $repository = new SqlitePostsRepository($connectionMock);
        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage('Cannot find post: 8c4db23d-931d-4dd0-9c33-5eec4ee3cc58');

        $repository->get(new UUID('8c4db23d-931d-4dd0-9c33-5eec4ee3cc58'));
    }

    // проверяем, что репозиторий сохраняет данные в БД
    public function testItSavesPostToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock->expects($this->once())->method('execute')->with([
            ':uuid' => '8c4db23d-931d-4dd0-9c33-5eec4ee3cc58',
            ':author_uuid' => '6a3fc0cf-c30c-41d8-93ab-b5827f78d6d6',
            ':title' => 'some title',
            ':text' => 'some text',
        ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqlitePostsRepository($connectionStub);

        $user = new User(
            new UUID('6a3fc0cf-c30c-41d8-93ab-b5827f78d6d6'),
            'name',
            new Name('first_name', 'last_name')
        );

        $repository->save(
            new Post(
                new UUID('8c4db23d-931d-4dd0-9c33-5eec4ee3cc58'),
                $user,
                'some title',
                'some text'
            )
        );
    }
}
