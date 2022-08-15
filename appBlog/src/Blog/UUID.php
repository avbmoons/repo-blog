<?php

namespace Elena\AppBlog\Blog;

use Elena\AppBlog\Blog\Exceptions\InvalidArgumentException;

class UUID
{
    // Внутри объекта храним UUID как строку
    //private string $uuidString;
    public function __construct(private string $uuidString)
    {
        // Если входная строка не подходит по формату -
        // бросаем исключение InvalidArgumentException
        // (его мы тоже добавили)
        //
        // Таким образом, мы гарантируем, что если объект
        // был создан, то он точно содержит правильный UUID
        if (!uuid_is_valid($uuidString)) {
            throw new InvalidArgumentException(
                "Malformed UUID: $this->uuidString"
            );
        }
    }

    // Генерирация нового случайного UUID
    public static function random(): self
    {
        return new self(uuid_create(UUID_TYPE_RANDOM));
    }

    public function __toString(): string
    {
        return $this->uuidString;
    }
}
