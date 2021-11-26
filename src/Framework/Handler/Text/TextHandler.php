<?php

namespace Powernic\Bot\Framework\Handler\Text;

use Powernic\Bot\Framework\Handler\AvailableMessageInterface;
use Powernic\Bot\Framework\Handler\RouteHandler;

abstract class TextHandler extends RouteHandler implements AvailableMessageInterface
{
    protected string $action;

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }
}
