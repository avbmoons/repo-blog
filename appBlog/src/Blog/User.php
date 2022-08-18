<?php

namespace Elena\AppBlog\Blog;

use Elena\AppBlog\Person\Name;
//use Elena\AppBlog\Blog\UUID;

class User
{
    public function __construct(
        private UUID $uuid,
        private string $username,
        private Name $name
    ) {
    }

    public function username(): string
    {
        return $this->username;
    }

    public function __toString(): string
    {
        $first_name = $this->name()->first();
        $last_name = $this->name()->last();
        return "Пользователь $first_name $last_name" . PHP_EOL;
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * @return Name
     */
    public function name(): Name
    {
        return $this->name;
    }
}
