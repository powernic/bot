<?php

namespace Powernic\Bot\Emias\Entity;

use DateTime; 
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

/**
 * @Embeddable()
 */
class Schedule
{

    /**
     * @Column(type="date", nullable=true)
     */
    private DateTime $date;

    /**
     * @Column(type="string", nullable=true)
     */
    private string $address;

    /**
     * @Column(type="string", nullable=true)
     */
    private string $roomNumber;

    /**
     * @Column(type="datetimetz", nullable=true)
     */
    private DateTime $startTime;

    /**
     * @Column(type="datetimetz", nullable=true)
     */
    private DateTime $endTime;

    public function __construct(
        string $date,
        string $address,
        string $roomNumber,
        string $startTime,
        string $endTime,
    ) {
        $this->date = new DateTime($date);
        $this->roomNumber = $roomNumber;
        $this->startTime = new DateTime($startTime);
        $this->endTime = new DateTime($endTime);
        $this->address = $address;
    }

    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getRoomNumber(): string
    {
        return $this->roomNumber;
    }

    /**
     * @return DateTime
     */
    public function getEndTime(): DateTime
    {
        return $this->endTime;
    }

    public function isNull(): bool
    {
        return !isset($this->date);
    }

}
