<?php

namespace Powernic\Bot\CommandHandler;

use TelegramBot\Api\Types\Message;

interface CommandHandlerInterface
{
    public function handle(Message $message): void;
}
