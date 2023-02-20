<?php

namespace App\Publish;

class PublishMessage
{

    public function __construct(public readonly string $id, public readonly string $user, public readonly bool $firstPublication)
    {
    }
}