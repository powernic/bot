<?php

namespace Powernic\Bot\Framework\Chat\Calendar;

use Powernic\Bot\Framework\Chat\Calendar\Selector\Selector;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class Calendar
{
    private Selector $selector;

    public function __construct(Selector $selector)
    {
        $this->selector = $selector;
    }

    public function render(): InlineKeyboardMarkup
    {
        return new InlineKeyboardMarkup($this->selector->getButtons());
    }
}

