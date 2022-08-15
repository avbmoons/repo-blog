<?php

namespace Elena\AppBlog\Blog;

use Elena\AppBlog\Person\Person;

class Comment
{

    public function __construct(
        private ?UUID $uuid = null,
        private ?UUID $post_uuid = null,
        private ?UUID $author_uuid = null,
        private ?string $text = null,
    ) {
    }

    public function __toString()
    {
        return 'комментарий для поста: ' . $this->post_uuid . ' от автора ' . $this->author_uuid . ': ' . $this->text;
    }

    public function getUuid(): ?UUID
    {
        return $this->uuid;
    }

    public function setUuid(?UUID $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getPostUuid(): ?UUID
    {
        return $this->post_uuid;
    }

    public function setPostUuid(?UUID $post_uuid): void
    {
        $this->post_uuid = $post_uuid;
    }

    public function getAuthorUuid(): ?UUID
    {
        return $this->author_uuid;
    }

    public function setAuthorUuid(?UUID $author_uuid): void
    {
        $this->author_uuid = $author_uuid;
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
