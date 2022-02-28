<?php

namespace Powernic\Bot\Framework\Chat\Calendar\Switcher;

use DateTime;

abstract class Switcher
{
    protected DateTime $targetDate;
    protected DateTime $currentDate;

    public function __construct(DateTime $targetDate, DateTime $currentDate)
    {
        $this->targetDate = $targetDate;
        $this->currentDate = $currentDate;
    }

    public function createPrevButton(): SwitchButton
    {
        if ($this->canSwitchToPrev()) {
            $previousValue = $this->getPreviousValue();
            return new SwitchButton("<<", $previousValue);
        } else {
            return $this->createEmptyButton();
        }
    }


    public function createNextButton(): SwitchButton
    {
        if ($this->canSwitchToNext()) {
            $nextValue = $this->getNextValue();
            return new SwitchButton(">>", $nextValue);
        } else {
            return $this->createEmptyButton();
        }
    }

    abstract protected function getCurrentValue(): int;

    private function createEmptyButton(): SwitchButton
    {
        return new SwitchButton(" ", $this->getCurrentValue());
    }

    protected function getCurrentYear(): int
    {
        return (int)$this->currentDate->format('Y');
    }

    abstract protected function canSwitchToNext(): bool;

    abstract protected function canSwitchToPrev(): bool;

    private function getPreviousValue(): int
    {
        return $this->getCurrentValue() - 1;
    }

    private function getNextValue(): int
    {
        return $this->getCurrentValue() + 1;
    }
}
