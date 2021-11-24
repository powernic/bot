<?php

namespace Powernic\Bot\Framework\Handler;

use TelegramBot\Api\Types\Update;

interface AvailableUpdateInterface
{
    public function setUpdate(Update $update): HandlerInterface;
}
