<?php

namespace Powernic\Bot\Framework\Chat\Calendar\Selector;

use DateTime;
use Powernic\Bot\Framework\Chat\Calendar\Button;
use Powernic\Bot\Framework\Chat\Calendar\Selector\CallbackData\CallbackDataFactory;
use Powernic\Bot\Framework\Chat\Calendar\Selector\CallbackData\DayDataFactory;
use Powernic\Bot\Framework\Chat\Calendar\Selector\CallbackData\MonthDataFactory;
use Powernic\Bot\Framework\Chat\Calendar\Switcher\MonthSwitcher;

class DaySelector extends Selector
{
    private array $daysOfWeekNames = ["П", "В", "С", "Ч", "П", "С", "В"];
    private array $monthNames = ['Янв', 'Февр', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сент', 'Окт', 'Нояб', 'Дек'];

    protected function getHeaderButtons(): array
    {
        return $this->createButtons($this->daysOfWeekNames);
    }

    protected function getBodyButtons(): array
    {
        $countsDaysInWeek = 7;
        $countDays = (int)$this->selectedDate->format('t');
        $month = $this->selectedDate->format('m');
        $year = $this->selectedDate->format('Y');
        $firstDayInMonth = DateTime::createFromFormat("d/m/Y", "1/{$month}/{$year}");
        $daysOfWeekOfFirstDayInMonth = (int)$firstDayInMonth->format('N');
        $lastDayInMonth = DateTime::createFromFormat("d/m/Y", "{$countDays}/{$month}/{$year}");
        $inactiveDays = $this->getInactiveDays();
        $daysOfWeekOfLastDayInMonth = (int)$lastDayInMonth->format('N');
        $countEmptyButtonsInFirstRow = $daysOfWeekOfFirstDayInMonth - 1;
        $countEmptyButtonsInLastRow = $countsDaysInWeek - $daysOfWeekOfLastDayInMonth;
        $gridCellCounts = $countEmptyButtonsInFirstRow + $countDays + $countEmptyButtonsInLastRow;
        $gridRowCounts = $gridCellCounts / $countsDaysInWeek;
        $buttons = [];
        $gridValues = array_merge(
            $this->createEmptyButtons($countEmptyButtonsInFirstRow),
            $this->createDayButtons($inactiveDays, $countDays),
            $this->createEmptyButtons($countEmptyButtonsInLastRow)
        );
        $valueIndex = 0;
        for ($gridRowIndex = 0; $gridRowIndex < $gridRowCounts; $gridRowIndex++) {
            $buttonsInline = [];
            for ($gridColumnIndex = 0; $gridColumnIndex < $countsDaysInWeek; $gridColumnIndex++) {
                $buttonsInline[] = $gridValues[$valueIndex];
                $valueIndex++;
            }
            if (!$this->allButtonsIsEmpty($buttonsInline)) {
                $lineButtons = $this->createLineButtons($buttonsInline);
                $buttons[] = $lineButtons;
            }
        }
        return $buttons;
    }

    private function createDayButtons(int $inactiveDays, int $count): array
    {
        return array_map(
            fn(int $day) => $inactiveDays > $day ? new Button(" ", 0) : new Button($day, $day),
            range(1, $count)
        );
    }

    /**
     * @param int $count
     * @return Button[]
     */
    private function createEmptyButtons(int $count): array
    {
        return array_map(fn() => new Button(' ', 0), range(1, $count));
    }

    protected function getFooterButtons(): array
    {
        $switcher = new MonthSwitcher($this->selectedDate, new DateTime());
        $selectedYear = (int)$this->selectedDate->format('Y');
        $selectedMonthNumber = (int)$this->selectedDate->format('m');
        $selectedMonth = $this->monthNames[$selectedMonthNumber - 1];
        $prevButton = $switcher->createPrevButton();
        $nextButton = $switcher->createNextButton();
        $monthSelector = $this->getParentSelector();
        $yearSelector = $monthSelector->getParentSelector();
        return [
            $monthSelector->createButton($prevButton->getText(), $prevButton->getValue()),
            $yearSelector->createButton($selectedMonth . " " . $selectedYear, $selectedYear),
            $monthSelector->createButton($nextButton->getText(), $nextButton->getValue())
        ];
    }

    public function getMessage(): string
    {
        return "Выберите день";
    }

    /**
     * @return int
     */
    private function getInactiveDays(): int
    {
        $currentDate = new DateTime();
        $isTheSameYear = (int)$currentDate->format('Y') === (int)$this->selectedDate->format('Y');
        $isTheSameMonth = (int)$currentDate->format('m') === (int)$this->selectedDate->format('m');
        if ($isTheSameYear && $isTheSameMonth) {
            return (int)$currentDate->format('d');
        }
        return 0;
    }
}
