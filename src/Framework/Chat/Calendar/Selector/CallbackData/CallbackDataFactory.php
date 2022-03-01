<?php

namespace Powernic\Bot\Framework\Chat\Calendar\Selector\CallbackData;

use DateTime;

abstract class CallbackDataFactory
{
    protected string $callbackPrefix;
    protected DateTime $selectedDate;

    public function __construct(string $calendarRoute, ?DateTime $selectedDate = null)
    {
        $this->callbackPrefix = $this->createCallbackPrefix($calendarRoute);
        $this->selectedDate = is_null($selectedDate) ? new DateTime() : $selectedDate;
    }

    protected function createCallbackPrefix(string $calendarRoute): string
    {
        return preg_replace('/:\d{1,4}:\d{1,2}:\d{1,2}:\d$/', '', $calendarRoute);
    }

    protected function getYear(int $value): int
    {
        return 0;
    }

    protected function getMonth(int $value): int
    {
        return 0;
    }

    protected function getDay(int $value): int
    {
        return 0;
    }

    protected function getDayPeriod(int $value): int
    {
        return 0;
    }

    public function create(int $value): string
    {
        $year = $this->getYear($value);
        $month = $this->getMonth($value);
        $day = $this->getDay($value);
        $dayPeriod = $this->getDayPeriod($value);
        return "{$this->callbackPrefix}:{$year}:{$month}:{$day}:{$dayPeriod}";
    }
}
