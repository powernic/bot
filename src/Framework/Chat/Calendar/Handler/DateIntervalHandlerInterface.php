<?php

namespace Powernic\Bot\Framework\Chat\Calendar\Handler;
 
use DateTime;

interface DateIntervalHandlerInterface
{
    public function handleDateInterval(DateTime $startTime, DateTime $endTime): void;
}
