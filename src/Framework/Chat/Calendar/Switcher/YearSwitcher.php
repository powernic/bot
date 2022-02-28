<?php

namespace Powernic\Bot\Framework\Chat\Calendar\Switcher;

class YearSwitcher extends Switcher
{

    public function canSwitchToNext(): bool
    {
        $maxYear = $this->getCurrentYear() + 1;
        $targetYear = $this->getCurrentValue();
        return $targetYear < $maxYear;
    }

    public function canSwitchToPrev(): bool
    {
        $minYear = $this->getCurrentYear();
        $targetYear = $this->getCurrentValue();
        return $targetYear > $minYear;
    }

    protected function getCurrentValue(): int
    {
        return (int)$this->targetDate->format('Y');
    }
}
