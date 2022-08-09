<?php

namespace Elena\AppBlog\Blog;

use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\Post;

class Comment
{
    private int $id;
    private int $user_id;
    private int $post_id;
    private string $text;

    public function __construct(int $id, User $user, Post $post, string $text)
    {
        $this->id = $id;
        $this->user_id = $user->getId();
        $this->post_id = $post->getId();
        $this->text = $text;
    }

    public function __toString()
    {
        return $this->getText();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void //
    {
        $this->text = $text;
    }
}
