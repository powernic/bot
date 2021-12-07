<?php

namespace Powernic\Bot\Emias\Entity;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\DoctorSubscription;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\SpecialitySubscription;

/**
 * @Entity()
 * @Table(name="emias_schedule_info")
 */
class ScheduleInfo
{

    /**
     * @Id()
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer", name="id")
     */
    private int $primaryId;

    private string $id;

    /**
     * @Column(type="date")
     */
    private $date;
    /**
     * @Column(type="string")
     */
    private string $address;
    /**
     * @Column(type="string")
     */
    private string $roomNumber;
    /**
     * @Column(type="datetimetz")
     * @var \DateTimeImmutable
     */
    private $startTime;
    /**
     * @Column(type="datetimetz")
     * @var \DateTimeImmutable
     */
    private $endTime;

    private int $appointmentTypeCode;

    /**
     * @OneToOne(targetEntity=\Powernic\Bot\Emias\Subscription\Doctor\Entity\SpecialitySubscription::class, mappedBy="scheduleInfo")
     */
    private SpecialitySubscription $specialitySubscription;

    /**
     * @OneToOne(targetEntity=\Powernic\Bot\Emias\Subscription\Doctor\Entity\doctorSubscription::class, mappedBy="scheduleInfo")
     */
    private DoctorSubscription $doctorSubscription;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): self
    {
        $this->date = new DateTimeImmutable($date);
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoomNumber(): string
    {
        return $this->roomNumber;
    }

    /**
     * @param string $roomNumber
     */
    public function setRoomNumber(string $roomNumber): self
    {
        $this->roomNumber = $roomNumber;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getStartTime(): DateTimeInterface
    {
        return $this->startTime;
    }

    /**
     * @param string $startTime
     */
    public function setStartTime(string $startTime): self
    {
        $this->startTime = new DateTimeImmutable($startTime);
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getEndTime(): DateTimeInterface
    {
        return $this->endTime;
    }

    /**
     * @param string $endTime
     */
    public function setEndTime(string $endTime): self
    {
        $this->endTime = new DateTimeImmutable($endTime);
        return $this;
    }

    /**
     * @return int
     */
    public function getAppointmentTypeCode(): int
    {
        return $this->appointmentTypeCode;
    }

    /**
     * @param int $appointmentTypeCode
     * $appointmentTypeCode
     */
    public function setAppointmentTypeCode(int $appointmentTypeCode): self
    {
        $this->appointmentTypeCode = $appointmentTypeCode;
        return $this;
    }

    /**
     * @return SpecialitySubscription
     */
    public function getSpecialitySubscription(): SpecialitySubscription
    {
        return $this->specialitySubscription;
    }

    /**
     * @return int
     */
    public function getPrimaryId(): int
    {
        return $this->primaryId;
    }
}