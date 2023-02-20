<?php

namespace App\Retire;

class RetireMessage
{

    public function __construct(public readonly int $id, public readonly string $user)
    {
    }
}