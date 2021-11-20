<?php

namespace Powernic\Bot\Framework\TextHandler;

use TelegramBot\Api\Types\Update;

interface TextHandlerInterface
{
    public function handle(Update $update): void;
}
