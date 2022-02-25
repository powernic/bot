<?php

namespace Powernic\Bot\Emias\API\Entity;

class DayScheduleDto
{
    public string $date;
    /**
     * @var SlotScheduleDto[]
     */
    public array $scheduleBySlot;
}
