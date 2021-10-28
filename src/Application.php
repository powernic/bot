<?php

namespace Powernic\Bot;

class Application
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
