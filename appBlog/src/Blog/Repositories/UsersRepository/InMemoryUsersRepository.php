<?php

namespace Elena\AppBlog\Blog\Repositories\UsersRepository;

use Elena\AppBlog\Blog\Exceptions\UserNotFoundException;
use Elena\AppBlog\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\UUID;

class InMemoryUsersRepository implements UsersRepositoryInterface
{
    /**
     * @var User[]
     */
    private array $users = [];

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->users[] = $user;
    }

    public function get(UUID $uuid): User
    {
        // Сравниваем строковые представления UUID
        foreach ($this->users as $user) {
            if ((string)$user->uuid() === (string)$uuid) {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found: $uuid");
    }

    public function getByUsername(string $username): User
    {
        foreach ($this->users as $user) {
            if ($user->username() === $username) {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found: $username");
    }
}
