<?php

namespace Powernic\Bot\Framework\Handler;

interface AvailableRouteInterface
{
    public function setRoute(string $route): self;
}
