<?php

namespace Powernic\Bot\Framework\Chat\Calendar\Selector;

use DateTime;
use Powernic\Bot\Framework\Chat\Calendar\Button;
use Powernic\Bot\Framework\Chat\Calendar\Switcher\YearSwitcher;

class MonthSelector extends Selector
{
    private array $months = [
        1 => 'Янв',
        2 => 'Февр',
        3 => 'Мар',
        4 => 'Апр',
        5 => 'Май',
        6 => 'Июн',
        7 => 'Июл',
        8 => 'Авг',
        9 => 'Сент',
        10 => 'Окт',
        11 => 'Нояб',
        12 => 'Дек'
    ];

    protected function getBodyButtons(): array
    {
        $buttons = [];
        $monthsInRow = 3;
        $monthsInColumn = 4;
        $inactiveMonths = $this->getInactiveMonths();
        for ($rowIndex = 0; $rowIndex < $monthsInColumn; $rowIndex++) {
            $fromMonth = ($rowIndex * $monthsInRow) + 1;
            $toMonth = ($rowIndex + 1) * $monthsInRow;
            $lineButtons = $this->createMonthButtons(
                $inactiveMonths,
                $fromMonth,
                $toMonth
            );
            if (!$this->allButtonsIsEmpty($lineButtons)) {
                $buttons[] = $this->createLineButtons($lineButtons);
            }
        }
        return $buttons;
    }

    /**
     * @param int $inactiveMonths
     * @return Button[]
     */
    private function createMonthButtons(int $inactiveMonths, int $fromMonth, int $toMonth): array
    {
        return array_map(
            fn(int $monthNumber) => $inactiveMonths > $monthNumber ? new Button(" ", 0) : new Button(
                $this->months[$monthNumber],
                $monthNumber
            ),
            range($fromMonth, $toMonth)
        );
    }

    private function getInactiveMonths(): int
    {
        $currentDate = new DateTime();
        $isTheSameYear = (int)$currentDate->format('Y') === (int)$this->selectedDate->format('Y');
        if ($isTheSameYear) {
            return (int)$currentDate->format('m');
        }
        return 0;
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
