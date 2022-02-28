<?php

namespace Powernic\Bot\Framework\Chat\Calendar\Selector;

class DayPeriodSelector extends Selector
{

    protected function getBodyButtons(): array
    {
        return [$this->createButtons(
            [
                DayPeriodEnum::AM->value => 'До 12:00',
                DayPeriodEnum::PM->value => 'После 12:00'
            ]
        )];
    }

    public function getMessage(): string
    {
        return "Выберите период времени";
    }
}
