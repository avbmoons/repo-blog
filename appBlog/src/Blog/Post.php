<?php

namespace Elena\AppBlog\Blog;

use Elena\AppBlog\Person\Person;

class Post
{

    public function __construct(
        private ?UUID $uuid = null,
        private ?UUID $author_uuid = null,
        private ?string $title = null,
        private ?string $text = null,
    ) {
    }

    public function __toString()
    {
        return $this->author_uuid . ' пишет: ' . $this->text;
    }

    public function getUuid(): ?UUID
    {
        return $this->uuid;
    }

    public function setUuid(?UUID $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getAuthorUuid(): ?UUID
    {
        return $this->author_uuid;
    }

    public function setAuthorUuid(?UUID $author_uuid): void
    {
        $this->author_uuid = $author_uuid;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }
}
