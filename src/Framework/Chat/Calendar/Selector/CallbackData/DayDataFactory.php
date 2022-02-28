<?php

namespace Powernic\Bot\Framework\Chat\Calendar\Selector\CallbackData;

class DayDataFactory extends CallbackDataFactory
{
    protected function getYear(int $value): int
    {
        return (int)$this->selectedDate->format('Y');
    }

    protected function getMonth(int $value): int
    {
        return (int)$this->selectedDate->format('m');
    }

    protected function getDay(int $value): int
    {
        return $value;
    }

}
