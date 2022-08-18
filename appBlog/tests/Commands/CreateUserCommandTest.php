<?php

namespace Elena\AppBlog\Blog\UnitTests\Commands;

use Elena\AppBlog\Blog\Commands\Arguments;
use Elena\AppBlog\Blog\Commands\CreateUserCommand;
use Elena\AppBlog\Blog\Exceptions\ArgumentsException;
use Elena\AppBlog\Blog\Exceptions\CommandException;
use Elena\AppBlog\Blog\Exceptions\UserNotFoundException;
use Elena\AppBlog\Blog\Repositories\UsersRepository\DummyUsersRepository;
use Elena\AppBlog\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\UUID;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{
    public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
    {

        $command = new CreateUserCommand(new DummyUsersRepository());

        $this->expectException(CommandException::class);
        $this->expectExceptionMessage('User already exists: Boris');

        $command->handle(new Arguments(['username' => 'Boris']));
    }

    // Функция возвращает объект типа UsersRepositoryInterface
    private function makeUsersRepository(): UsersRepositoryInterface
    {
        return new class implements UsersRepositoryInterface
        {
            public function save(User $user): void
            {
            }
            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }
            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException("Not found");
            }
        };
    }

    // Тест проверяет, что команда действительно требует фамилию пользователя
    public function testItRequiresLastName(): void
    {
        $command = new CreateUserCommand($this->makeUsersRepository());
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('No such argument: last_name');
        $command->handle(new Arguments([
            'username' => 'Boris',
            'first_name' => 'Boris',
        ]));
    }

    // Тест, проверяющий, что команда сохраняет пользователя в репозитории
    public function testItSavesUserToRepository(): void
    {
        $usersRepository = new class implements UsersRepositoryInterface
        {

            private bool $called = false;
            public function save(User $user): void
            {
                $this->called = true;
            }
            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }
            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function wasCalled(): bool
            {
                return $this->called;
            }
        };

        // Передаём наш мок в команду
        $command = new CreateUserCommand($usersRepository);

        // Запускаем команду
        $command->handle(new Arguments([
            'username' => 'Boris',
            'first_name' => 'Boris',
            'last_name' => 'Nikitin',
        ]));

        // Проверяем утверждение относительно мока,
        // а не утверждение относительно команды
        $this->assertTrue($usersRepository->wasCalled());
    }
}
