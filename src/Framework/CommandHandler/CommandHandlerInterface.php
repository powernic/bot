<?php

namespace Powernic\Bot\Framework\CommandHandler;

use TelegramBot\Api\Types\Message;

interface CommandHandlerInterface
{
    public function handle(Message $message): void;
}
