<?php

namespace Powernic\Bot\Framework\Handler;

use TelegramBot\Api\Types\CallbackQuery;

interface AvailableCallbackQueryInterface
{
    public function setCallbackQuery(CallbackQuery $callbackQuery): HandlerInterface;
}
