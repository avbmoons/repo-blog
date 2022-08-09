<?php

namespace Common;

class Unit
{
    protected ?int $id;
    public ?string $name;
    public ?int $age;

    public function __construct(int $id = null, string $name = null, int $age = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->age = $age;
    }

    public function id()
    {
        return $this->id;
    }

    public function __toString()
    {
        return "Hi from " . $this->name . PHP_EOL;
    }
}
