<?php

namespace Powernic\Bot\Framework\Chat\Calendar\Selector;

use DateTime;
use Powernic\Bot\Framework\Chat\Calendar\Switcher\YearSwitcher;

class MonthSelector extends Selector
{
    protected function getBodyButtons(): array
    {
        return $this->createButtonsFromMatrix([
            [1 => 'Янв', 2 => 'Февр', 3 => 'Мар'],
            [4 => 'Апр', 5 => 'Май', 6 => 'Июн'],
            [7 => 'Июл', 8 => 'Авг', 9 => 'Сент'],
            [10 => 'Окт', 11 => 'Нояб', 12 => 'Дек']
        ]);
    }

    protected function getFooterButtons(): array
    {
        $switcher = new YearSwitcher($this->selectedDate, new DateTime());
        $selectedYear = (int)$this->selectedDate->format('Y');
        $prevButton = $switcher->createPrevButton();
        $nextButton = $switcher->createNextButton();
        $yearSelector = $this->getParentSelector();
        return [
            $yearSelector->createButton($prevButton->getText(), $prevButton->getValue()),
            $yearSelector->createButton($selectedYear, 0),
            $yearSelector->createButton($nextButton->getText(), $nextButton->getValue()),
        ];
    }

    public function getMessage(): string
    {
        return "Выберите месяц";
    }
}
