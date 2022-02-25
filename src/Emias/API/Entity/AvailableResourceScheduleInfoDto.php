<?php

namespace Powernic\Bot\Emias\API\Entity;

class AvailableResourceScheduleInfoDto
{
    public DoctorInfoDto $availableResource;
    /**
     * @var DayScheduleDto[]
     */
    public array $scheduleOfDay;
}
