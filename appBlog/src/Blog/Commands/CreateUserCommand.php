<?php

namespace Elena\AppBlog\Blog\Commands;

use Elena\AppBlog\Blog\Exceptions\ArgumentsException;
use Elena\AppBlog\Blog\Exceptions\CommandException;
use Elena\AppBlog\Blog\Exceptions\UserNotFoundException;
use Elena\AppBlog\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\UUID;
use Elena\AppBlog\Person\Name;

//use Elena\AppBlog\Blog\Commands\Arguments;

class CreateUserCommand
{
    public function __construct(private UsersRepositoryInterface $userRepository)
    {
        //$this->userRepository = $userRepository;
    }

    /**
     * @throws ArgumentsException
     * @throws CommandException
     */
    public function handle(Arguments $arguments): void
    {
        $username = $arguments->get('username');

        // Проверяем, существует ли пользователь в репозитории
        if ($this->userExists($username)) {
            // Бросаем исключение, если пользователь уже существует
            throw new CommandException("User already exists: $username");
        }
        // Сохраняем пользователя в репозиторий
        $this->userRepository->save(new User(
            UUID::random(),
            $username,
            new Name($arguments->get('first_name'), $arguments->get('last_name'))
        ));
    }

    private function userExists(string $username): bool
    {
        try {
            // Пытаемся получить пользователя из репозитория
            $this->userRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }
}
