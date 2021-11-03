<?php

namespace Powernic\Bot\CallbackHandler;

use TelegramBot\Api\Types\CallbackQuery;
use TelegramBot\Api\Types\Update;

interface CallbackHandlerInterface
{
    public function handle(): void;

    public function setQuery(CallbackQuery $query): self;

    public function textHandle(): void;

    public function setUpdate(Update $update): self;
}
