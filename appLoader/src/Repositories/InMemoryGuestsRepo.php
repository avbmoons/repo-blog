<?php

namespace Repositories;

use Units\Guest;
use Exceptions\GuestNotFoundException;

class InMemoryGuestsRepo
{
    private ?array $guests = [];

    public function save(Guest $guest): void
    {
        $this->guests[] = $guest;
    }

    public function get(int $id): Guest
    {
        foreach ($this->guests as $guest) {
            if ($guest->id() === $id) {
                return $guest;
            }
        }
        throw new GuestNotFoundException("No there guest!", 404);
    }
}
