<?php

namespace Elena\AppBlog\Blog;

use Elena\AppBlog\Blog\User;

class Post
{
    private int $id;
    private int $user_id;
    private string $heading;
    private string $text;

    public function __construct(int $id, User $user, string $heading, string $text)

    {
        $this->id = $id;
        $this->user_id = $user->getId();
        $this->heading = $heading;
        $this->text = $text;
    }

    public function __toString()
    {
        return $this->getHeading() . PHP_EOL . $this->getText();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void    //
    {
        $this->id = $id;
    }

    public function getHeading(): string
    {
        return $this->heading;
    }

    public function setHeading(string $heading): void   //
    {
        $this->heading = $heading;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void   //
    {
        $this->text = $text;
    }
}
