<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\Entity;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Powernic\Bot\Emias\Entity\Schedule;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Emias\Policy\Entity\Policy;

abstract class Subscription
{
    /**
     * @Column(type="datetimetz", name="start_time_interval", nullable="true")
     */
    protected ?DateTime $startTimeInterval = null;
    /**
     * @Embedded(class=\Powernic\Bot\Emias\Entity\Schedule::class, columnPrefix = "schedule_")
     */
    protected ?Schedule $schedule = null;
    /**
     * @Id()
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer")
     */
    protected string $id;
    /**
     * @ManyToOne(targetEntity=\Powernic\Bot\Emias\Policy\Entity\Policy::class, inversedBy="specialitySubscriptions")
     */
    protected ?Policy $policy;
    /**
     * @Column(type="datetimetz", name="end_time_interval", nullable="true")
     */
    protected ?DateTime $endTimeInterval = null;


    /**
     * @param ?DateTime $endTimeInterval
     */
    public function setEndTimeInterval(?DateTime $endTimeInterval): self
    {
        $this->endTimeInterval = $endTimeInterval;

        return $this;
    }

    /**
     * @return ?DateTime
     */
    public function getEndTimeInterval(): ?DateTime
    {
        return $this->endTimeInterval;
    }

    /**
     * @param ?DateTime $startTimeInterval
     */
    public function setStartTimeInterval(?DateTime $startTimeInterval): self
    {
        $this->startTimeInterval = $startTimeInterval;

        return $this;
    }

    /**
     * @return Policy
     */
    public function getPolicy(): Policy
    {
        return $this->policy;
    }

    /**
     * @return ?DateTime
     */
    public function getStartTimeInterval(): ?DateTime
    {
        return $this->startTimeInterval;
    }

    /**
     * @return ?Schedule
     */
    public function getSchedule(): ?Schedule
    {
        return is_null($this->schedule) || $this->schedule->isNull() ? null : $this->schedule;
    }

    /**
     * @param Policy $policy
     */
    public function setPolicy(Policy $policy): self
    {
        $this->policy = $policy;

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param ?Schedule $schedule
     * @return self
     */
    public function setSchedule(?Schedule $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function hasTargetTimeInterval(): bool
    {
        return isset($this->startTimeInterval);
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    abstract public function getSpeciality(): Speciality;
}
