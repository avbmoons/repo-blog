<?php

namespace Units;

use Common\Unit;

class Member extends Unit
{

    private ?array $inventory = [];

    public function __construct(int $id = null, string $name = null, int $age = null, array $inventory = [null])
    {
        parent::__construct($id, $name, $age);
        $this->inventory = $inventory;
    }
}
