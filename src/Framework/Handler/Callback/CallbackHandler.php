<?php

namespace Powernic\Bot\Framework\Handler\Callback;

use Powernic\Bot\Framework\Handler\AvailableMessageInterface;
use Powernic\Bot\Framework\Handler\AvailableRouteInterface;
use Powernic\Bot\Framework\Handler\RouteHandler;

abstract class CallbackHandler extends RouteHandler implements AvailableRouteInterface, AvailableMessageInterface
{
}
