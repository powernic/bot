<?php

namespace Powernic\Bot\Framework\Handler;

use TelegramBot\Api\Types\Message;

interface AvailableMessageInterface
{
    public function setMessage(Message $message): self;
}
