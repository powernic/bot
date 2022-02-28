<?php

namespace Framework\Chat\Calendar;

use DateTime;
use PHPUnit\Framework\TestCase;
use Powernic\Bot\Framework\Chat\Calendar\Switcher\YearSwitcher;

class YearSwitcherTest extends TestCase
{

    /**
     * @dataProvider canSwitchToNextYearProvider
     */
    public function testCanSwitchToNextYear(string $date, bool $expectedResult)
    {
        $daySelector = new YearSwitcher(
            DateTime::createFromFormat('d/m/Y', $date),
            DateTime::createFromFormat('d/m/Y', '25/02/2022')
        );
        $this->assertEquals($expectedResult, $daySelector->canSwitchToNext(), $date);
    }

    public function canSwitchToNextYearProvider(): array
    {
        return [
            ["01/01/2022", true],
            ["01/12/2022", true],
            ["01/01/2023", false],
            ["22/11/2023", false],
            ["31/11/2023", false],
            ["01/12/2023", false],
            ["31/12/2023", false],
            ["01/01/2024", false],
            ["12/01/2024", false]
        ];
    }

    /**
     * @dataProvider canSwitchToPreviousYearProvider
     */
    public function testCanSwitchToPreviousYear(string $date, bool $expectedResult)
    {
        $daySelector = new YearSwitcher(
            DateTime::createFromFormat('d/m/Y', $date),
            DateTime::createFromFormat('d/m/Y', '25/02/2022')
        );
        $this->assertEquals($expectedResult, $daySelector->canSwitchToPrev(), $date);
    }

    public function canSwitchToPreviousYearProvider(): array
    {
        return [
            ["01/01/2021", false],
            ["30/12/2021", false],
            ["01/12/2021", false],
            ["01/01/2022", false],
            ["20/01/2022", false],
            ["02/01/2022", false],
            ["01/02/2022", false],
            ["01/01/2023", true],
            ["22/11/2023", true],
            ["01/12/2023", true],
            ["31/12/2023", true],
            ["01/01/2024", true],
            ["12/01/2024", true]
        ];
    }
}
