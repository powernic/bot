<?php

namespace Powernic\Bot\Framework\Handler\Command;

use Powernic\Bot\Framework\Handler\AvailableMessageInterface;
use Powernic\Bot\Framework\Handler\Handler;
use ReflectionException;
use ReflectionProperty;
use TelegramBot\Api\Types\Message;

abstract class CommandHandler extends Handler implements AvailableMessageInterface
{
    protected Message $message;

    /**
     * @param Message $message
     * @return self
     */
    public function setMessage(Message $message): self
    {
        $this->message = $message;

        return $this;
    }


}
