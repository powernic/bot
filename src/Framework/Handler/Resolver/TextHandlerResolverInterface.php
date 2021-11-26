<?php

namespace Powernic\Bot\Framework\Handler\Resolver;

use TelegramBot\Api\Types\Message;

interface TextHandlerResolverInterface
{
    public function resolve(Message $message): string;
}
