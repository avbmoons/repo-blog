<?php

namespace Elena\AppBlog\Blog\Repositories\UsersRepository;

use Elena\AppBlog\Blog\Exceptions\UserNotFoundException;
use Elena\AppBlog\Blog\Repositories\UsersRepository\UsersRepositoryInterface;

use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\UUID;
use Elena\AppBlog\Person\Name;

class DummyUsersRepository implements UsersRepositoryInterface
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
        return new User(UUID::random(), "user123", new Name("first", "last"));
    }
}
