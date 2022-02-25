<?php

namespace Powernic\Bot\Emias\API\Entity;

class SlotScheduleDto
{
    public int $complexResourceId;
    public string $cabinetNumber;
    /**
     * @var SlotDto[]
     */
    public array $slot;
}
