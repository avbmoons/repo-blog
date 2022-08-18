<?php

namespace Elena\AppBlog\Blog;

use Elena\AppBlog\Person\Person;
use Elena\AppBlog\Blog\Post;

class Comment
{

    // public function __construct(
    //     private ?UUID $uuid = null,
    //     private ?UUID $post_uuid = null,
    //     private ?UUID $author_uuid = null,
    //     private ?string $text = null,
    // ) {
    // }

    public function __construct(
        private ?UUID $uuid = null,
        private Post $post,
        private User $user,
        private ?string $text = null,
    ) {
    }

    public function __toString()
    {
        return 'комментарий для поста: ' . $this->post_uuid . ' от автора ' . $this->author_uuid . ': ' . $this->text;
    }

    /**
     * @return UUID
     */
    public function getUuid(): ?UUID
    {
        return $this->uuid;
    }

    /**
     * @param UUID $uuid
     */
    public function setUuid(?UUID $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param Post $uuid
     * @return void
     */
    public function setPost(Post $uuid): void
    {
        $this->post = $uuid;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param Post $user
     * @return void
     */
    public function setUser(Post $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }
}
