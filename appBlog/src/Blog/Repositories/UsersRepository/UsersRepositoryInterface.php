<?php

namespace Elena\AppBlog\Blog\Repositories\UsersRepository;

use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\UUID;

interface UsersRepositoryInterface
{
    public function save(User $user): void;
    public function get(UUID $uuid): User;
    public function getByUsername(string $username): User;
}
