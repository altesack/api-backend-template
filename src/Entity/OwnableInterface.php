<?php

namespace App\Entity;

interface OwnableInterface
{
    public function getOwner(): User;

    public function setOwner(User $user): self;
}
