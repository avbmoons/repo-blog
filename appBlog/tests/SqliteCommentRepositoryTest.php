<?php

use Elena\AppBlog\Blog\Exceptions\UserNotFoundException;
use Elena\AppBlog\Blog\Exceptions\PostNotFoundException;
use Elena\AppBlog\Blog\Exceptions\CommentNotFoundException;
use Elena\AppBlog\Blog\Post;
use Elena\AppBlog\Blog\Comment;
use Elena\AppBlog\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Elena\AppBlog\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\UUID;
use Elena\AppBlog\Person\Name;
use PHPUnit\Framework\TestCase;
use Elena\AppBlog\Blog\Exceptions\InvalidArgumentException;

class SqliteCommentRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenCommentNotFound(): void
    {
        $connectionMock = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);
        $connectionMock->method('prepare')->willReturn($statementStub);

        $repository = new SqliteCommentsRepository($connectionMock);
        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage('Cannot find comment: ad66dfae-1ebd-47b4-bd70-a2d29ae77c9a');

        $repository->get(new UUID('ad66dfae-1ebd-47b4-bd70-a2d29ae77c9a'));
    }

    // проверяем, что репозиторий сохраняет данные в БД
    public function testItSavesCommentToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock->expects($this->once())->method('execute')->with([
            ':uuid' => 'd75f3a4a-b8dc-4c90-b007-423337795312',
            ':post_uuid' => '8c4db23d-931d-4dd0-9c33-5eec4ee3cc58',
            ':author_uuid' => '6a3fc0cf-c30c-41d8-93ab-b5827f78d6d6',
            ':text' => 'some text',
        ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqliteCommentsRepository($connectionStub);

        $user = new User(
            new UUID('6a3fc0cf-c30c-41d8-93ab-b5827f78d6d6'),
            'name',
            new Name('first_name', 'last_name')
        );
        $post = new Post(
            new UUID('8c4db23d-931d-4dd0-9c33-5eec4ee3cc58'),
            $user,
            'title',
            'text'
        );

        $repository->save(
            new Comment(
                new UUID('d75f3a4a-b8dc-4c90-b007-423337795312'),
                $post,
                $user,
                'some text'
            )
        );
    }
}
