<?php

namespace Powernic\Bot\Framework\Chat\Calendar\Selector\CallbackData;

class YearDataFactory extends CallbackDataFactory
{
    protected function getYear(int $value): int
    {
        return $value;
    }
}
