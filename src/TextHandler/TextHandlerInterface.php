<?php

namespace Powernic\Bot\TextHandler;

use TelegramBot\Api\Types\Update;

interface TextHandlerInterface
{
    public function handle(Update $update): void;
}
