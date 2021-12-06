<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use Powernic\Bot\Emias\Entity\ScheduleInfo;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Emias\Policy\Entity\Policy;

/**
 * @Entity()
 * @Table(name="emias_speciality_subscription")
 **/
class SpecialitySubscription
{

    /**
     * @Id()
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer")
     */
    private string $id;

    /**
     * @ManyToOne(targetEntity=\Powernic\Bot\Emias\Policy\Entity\Policy::class, inversedBy="specialitySubscription")
     */
    private ?Policy $policy;

    /**
     * @ManyToOne(targetEntity=\Powernic\Bot\Emias\Entity\Speciality::class, inversedBy="doctorSubscriptions")
     * @JoinColumn(nullable=true , referencedColumnName="code")
     */
    private ?Speciality $speciality;
    /**
     * @Column(type="datetimetz", name="start_time_interval", nullable="true")
     */
    private ?DateTimeImmutable $startTimeInterval;
    /**
     * @Column(type="datetimetz", name="end_time_interval", nullable="true")
     */
    private ?DateTimeImmutable $endTimeInterval;

    /**
     * @OneToOne(targetEntity=\Powernic\Bot\Emias\Entity\ScheduleInfo::class, inversedBy="doctorSubscription",
     *     cascade={"persist", "remove"})
     */
    private ?ScheduleInfo $scheduleInfo;

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
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Policy
     */
    public function getPolicy(): Policy
    {
        return $this->policy;
    }

    /**
     * @param Policy $policy
     */
    public function setPolicy(Policy $policy): self
    {
        $this->policy = $policy;

        return $this;
    }

    public function hasTargetTimeInterval(): bool
    {
        return isset($this->startTimeInterval);
    }

    /**
     * @return ?DateTimeImmutable
     */
    public function getStartTimeInterval(): ?DateTimeImmutable
    {
        return $this->startTimeInterval;
    }

    /**
     * @param ?DateTimeImmutable $startTimeInterval
     */
    public function setStartTimeInterval(?DateTimeImmutable $startTimeInterval): self
    {
        $this->startTimeInterval = $startTimeInterval;

        return $this;
    }

    /**
     * @return ?DateTimeImmutable
     */
    public function getEndTimeInterval(): ?DateTimeImmutable
    {
        return $this->endTimeInterval;
    }

    /**
     * @param ?DateTimeImmutable $endTimeInterval
     */
    public function setEndTimeInterval(?DateTimeImmutable $endTimeInterval): self
    {
        $this->endTimeInterval = $endTimeInterval;

        return $this;
    }

    /**
     * @param ?Speciality $speciality
     * @return self
     */
    public function setSpeciality(?Speciality $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * @return Speciality
     */
    public function getSpeciality(): Speciality
    {
        return $this->speciality;
    }

    /**
     * @param ?ScheduleInfo $scheduleInfo
     * @return self
     */
    public function setScheduleInfo(?ScheduleInfo $scheduleInfo): self
    {
        $this->scheduleInfo = $scheduleInfo;

        return $this;
    }

    /**
     * @return ?ScheduleInfo
     */
    public function getScheduleInfo(): ?ScheduleInfo
    {
        return $this->scheduleInfo;
    }


}
