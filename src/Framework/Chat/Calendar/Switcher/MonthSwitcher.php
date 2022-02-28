<?php

namespace Powernic\Bot\Framework\Chat\Calendar\Switcher;

use DateTime;

class MonthSwitcher extends Switcher
{

    protected function getCurrentValue(): int
    {
        return (int)$this->targetDate->format('m');
    }

    protected function canSwitchToNext(): bool
    {
        $maxYear = $this->getCurrentYear() + 1;
        $maxDate = DateTime::createFromFormat("d/m/Y", "1/12/{$maxYear}");
        return $this->targetDate < $maxDate;
    }

    protected function canSwitchToPrev(): bool
    {
        $minYear = $this->getCurrentYear();
        $firstMonthOfYear = DateTime::createFromFormat("d/m/Y", "1/1/{$minYear}");
        $countDays = $firstMonthOfYear->format('t');
        $minDate = DateTime::createFromFormat("d/m/Y", "{$countDays}/1/{$minYear}");
        return $this->targetDate > $minDate;
    }
}
