<?php

namespace Elena\AppBlog\Blog\Repositories\CommentsRepository;

use Elena\AppBlog\Blog\Comment;
//use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\UUID;

interface CommentsRepositoryInterface
{
    public function save(Comment $comment): void;
    public function get(UUID $uuid): Comment;
    //public function getByUsername(string $username): User;
}
