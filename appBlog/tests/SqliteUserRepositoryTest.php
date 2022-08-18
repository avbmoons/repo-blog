<?php

use Elena\AppBlog\Blog\Exceptions\UserNotFoundException;
use Elena\AppBlog\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\UUID;
use Elena\AppBlog\Person\Name;
use PHPUnit\Framework\TestCase;
// use PDO;
// use PDOStatement;

class SqliteUserRepositoryTest extends TestCase
{
    // Тест, проверяющий, что SQLite-репозиторий бросает исключение,
    // когда запрашиваемый пользователь не найден
    public function testItThrowsAnExceptionWhenUserNotFound(): void
    {
        $connectionMock = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);

        $statementStub->method('fetch')->willReturn(false);
        $connectionMock->method('prepare')->willReturn($statementStub);


        $repository = new SqliteUsersRepository($connectionMock);
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('Cannot find user: Ivan');

        $repository->getByUsername('Ivan');
    }

    // Тест, проверяющий, что репозиторий сохраняет данные в БД
    public function testItSavesUserToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock->expects($this->once())->method('execute')->with([
            ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
            ':username' => 'ivan123',
            ':first_name' => 'Ivan',
            ':last_name' => 'Nikitin',
        ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqliteUsersRepository($connectionStub);

        $repository->save(
            new User(
                new UUID('123e4567-e89b-12d3-a456-426614174000'),
                'ivan123',
                new Name('Ivan', 'Nikitin')
            )
        );
    }
}
