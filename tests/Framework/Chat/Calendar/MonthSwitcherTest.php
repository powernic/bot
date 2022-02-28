<?php

namespace Framework\Chat\Calendar;

use DateTime;
use PHPUnit\Framework\TestCase;
use Powernic\Bot\Framework\Chat\Calendar\Switcher\MonthSwitcher;

class MonthSwitcherTest extends TestCase
{

    /**
     * @dataProvider canSwitchToNextMonthProvider
     */
    public function testCanSwitchToNextMonth(string $date, bool $expectedResult)
    {
        $daySelector = new MonthSwitcher(
            DateTime::createFromFormat('d/m/Y', $date),
            DateTime::createFromFormat('d/m/Y', '25/02/2022')
        );
        $this->assertEquals($expectedResult, $daySelector->canSwitchToNext(), $date);
    }

    public function canSwitchToNextMonthProvider(): array
    {
        return [
            ["01/01/2022", true],
            ["01/12/2022", true],
            ["02/01/2023", true],
            ["22/11/2023", true],
            ["31/11/2023", false],
            ["01/12/2023", false],
            ["31/12/2023", false],
            ["01/01/2024", false],
            ["12/01/2024", false]
        ];
    }

    /**
     * @dataProvider canSwitchToPreviousMonthProvider
     */
    public function testCanSwitchToPreviousMonth(string $date, bool $expectedResult)
    {
        $daySelector = new MonthSwitcher(
            DateTime::createFromFormat('d/m/Y', $date),
            DateTime::createFromFormat('d/m/Y', '25/02/2022')
        );
        $this->assertEquals($expectedResult, $daySelector->canSwitchToPrev(), $date);
    }

    public function canSwitchToPreviousMonthProvider(): array
    {
        return [
            ["01/01/2021", false],
            ["30/12/2021", false],
            ["01/12/2021", false],
            ["01/01/2022", false],
            ["20/01/2022", false],
            ["02/01/2022", false],
            ["01/02/2022", true],
            ["01/12/2022", true],
            ["22/11/2023", true],
            ["01/12/2023", true],
            ["31/12/2023", true],
            ["01/01/2024", true],
            ["12/01/2024", true]
        ];
    }
}
