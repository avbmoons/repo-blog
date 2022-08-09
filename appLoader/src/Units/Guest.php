<?php

namespace Units;

use Common\Unit;

class Guest extends Unit
{

    public string $orga;
    public function Orga()
    {
        echo "Orga: " . $this->orga . PHP_EOL;
    }
}
