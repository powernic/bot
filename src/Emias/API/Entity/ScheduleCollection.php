<?php

namespace Powernic\Bot\Emias\API\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use LengthException;
use Powernic\Bot\Emias\Entity\Schedule;

/**
 * @method bool add(Schedule $element)
 * @method Schedule[] getIterator()
 */
class ScheduleCollection extends ArrayCollection
{

    /**
     * @param DateTimeInterface $targetDay
     * @return ?Schedule
     * @throws LengthException|Exception
     */
    public function getNearestInConcreteDay(DateTimeInterface $targetDay): ?Schedule
    {
        if ($this->isEmpty()) {
            return null;
        }
        $startDateInterval = new DateTimeImmutable($targetDay->format('Y-m-d 00:00:00'));
        $endDateInterval = new DateTimeImmutable($targetDay->format('Y-m-d 23:59:59'));
        $nearestSchedule = null;
        $isNearest = true;
        foreach ($this->getIterator() as $schedule) {
            $isAfterStartTargetDate = $startDateInterval < $schedule->getStartTime();
            $isBeforeEndTargetDate = $schedule->getStartTime() < $endDateInterval;
            if (isset($nearestSchedule)) {
                $isNearest = ($schedule->getStartTime() < $nearestSchedule->getStartTime());
            }
            if ($isNearest) {
                if ($isAfterStartTargetDate && $isBeforeEndTargetDate) {
                    $nearestSchedule = $schedule;
                }
            }
        }

        return $nearestSchedule;
    }

    /**
     * @return ?Schedule
     * @throws Exception
     */
    public function getNearestInAllDay(): ?Schedule
    {
        if ($this->isEmpty()) {
            return null;
        }
        $nearestSchedule = $this->first();
        foreach ($this->getIterator() as $schedule) {
            if ($schedule->getStartTime() < $nearestSchedule->getStartTime()) {
                $nearestSchedule = $schedule;
            }
        }

        return $nearestSchedule;
    }

    /**
     * @param ScheduleInfoDto[] $scheduleDtoCollection
     * @return ScheduleCollection
     */
    public static function createFromArrayDto(array $scheduleDtoCollection): self
    {
        $scheduleCollection = new self();
        foreach ($scheduleDtoCollection as $scheduleDto) {
            $schedule = new Schedule(
                $scheduleDto->date,
                $scheduleDto->address,
                $scheduleDto->roomNumber,
                $scheduleDto->startTime,
                $scheduleDto->endTime
            );
            $scheduleCollection->add($schedule);
        }
        return $scheduleCollection;
    }

    /**
     * @param AvailableResourceScheduleInfoDto $availableResourceScheduleDto
     * @return ScheduleCollection
     */
    public static function createFromAvailableResourceScheduleDto(
        AvailableResourceScheduleInfoDto $availableResourceScheduleDto
    ): self {
        $scheduleCollection = new self();
        $address = reset($availableResourceScheduleDto->availableResource->complexResource)
            ->room
            ->defaultAddress;
        foreach ($availableResourceScheduleDto->scheduleOfDay as $dayScheduleDto) {
            foreach ($dayScheduleDto->scheduleBySlot as $slotScheduleDto) {
                foreach ($slotScheduleDto->slot as $slotDto) {
                    $schedule = new Schedule(
                        $dayScheduleDto->date,
                        $address,
                        $slotScheduleDto->cabinetNumber,
                        $slotDto->startTime,
                        $slotDto->endTime
                    );
                    $scheduleCollection->add($schedule);
                }
            }
        }
        return $scheduleCollection;
    }
}
