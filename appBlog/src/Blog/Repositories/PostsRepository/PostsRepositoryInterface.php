<?php

namespace Elena\AppBlog\Blog\Repositories\PostsRepository;

use Elena\AppBlog\Blog\Post;
//use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\UUID;

interface PostsRepositoryInterface
{
    public function save(Post $post): void;
    public function get(UUID $uuid): Post;
    //public function getByUsername(string $username): User;
}
