<?php

namespace Powernic\Bot\Emias\Service;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use LengthException;
use Powernic\Bot\Emias\Entity\ScheduleInfo;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Emias\Policy\Entity\Policy;

class ScheduleInfoService
{
    /**
     * @param DateTimeInterface $targetDay
     * @param ScheduleInfo[] $scheduleInfoCollection
     * @return ScheduleInfo
     * @throws LengthException|Exception
     */
    public function getNearestScheduleInfoInConcreteDay(
        DateTimeInterface $targetDay,
        array $scheduleInfoCollection
    ): ScheduleInfo {
        if (empty($scheduleInfoCollection)) {
            throw new LengthException("ScheduleInfo Collection is empty");
        }
        $startDateInterval = new DateTimeImmutable($targetDay->format('Y-m-d 00:00:00'));
        $endDateInterval = new DateTimeImmutable($targetDay->format('Y-m-d 23:59:59'));
        $nearestSchedule = null;
        $isNearest = true;
        foreach ($scheduleInfoCollection as $scheduleInfo) {
            $isAfterStartTargetDate = $startDateInterval < $scheduleInfo->getStartTime();
            $isBeforeEndTargetDate = $scheduleInfo->getStartTime() < $endDateInterval;
            if (isset($nearestSchedule)) {
                $isNearest = ($scheduleInfo->getStartTime() < $nearestSchedule->getStartTime());
            }
            if ($isNearest) {
                if ($isAfterStartTargetDate && $isBeforeEndTargetDate) {
                    $nearestSchedule = $scheduleInfo;
                }
            }
        }

        return $nearestSchedule;
    }

    /**
     * @param ScheduleInfo[] $scheduleInfoCollection
     * @return ScheduleInfo
     */
    public function getNearestScheduleInfo(array $scheduleInfoCollection): ScheduleInfo
    {
        if (empty($scheduleInfoCollection)) {
            throw new LengthException("ScheduleInfo Collection is empty");
        }
        $firstScheduleInfo = reset($scheduleInfoCollection);
        $nearestSchedule = $firstScheduleInfo;
        foreach ($scheduleInfoCollection as $scheduleInfo) {
            if ($scheduleInfo->getStartTime() < $nearestSchedule->getStartTime()) {
                $nearestSchedule = $firstScheduleInfo;
            }
        }

        return $nearestSchedule;
    }
}
