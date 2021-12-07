<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use Powernic\Bot\Emias\Entity\Doctor;
use Powernic\Bot\Emias\Entity\ScheduleInfo;
use Powernic\Bot\Emias\Policy\Entity\Policy;

/**
 * @Entity()
 * @Table(name="emias_doctor_subscription")
 **/
class DoctorSubscription extends Subscription
{

    /**
     * @ManyToOne(targetEntity=\Powernic\Bot\Emias\Policy\Entity\Policy::class, inversedBy="doctorSubscriptions")
     */
    protected ?Policy $policy;

    /**
     * @OneToOne(targetEntity=\Powernic\Bot\Emias\Entity\ScheduleInfo::class, inversedBy="doctorSubscription",
     *     cascade={"persist", "remove"})
     */
    protected ?ScheduleInfo $scheduleInfo;

    /**
     * @ManyToOne(targetEntity=\Powernic\Bot\Emias\Entity\Doctor::class, inversedBy="doctorSubscriptions")
     * @JoinColumn(referencedColumnName="employeeId")
     */
    protected ?Doctor $doctor;

    /**
     * @return Doctor|null
     */
    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    /**
     * @param Doctor|null $doctor
     * @return DoctorSubscription
     */
    public function setDoctor(?Doctor $doctor): self
    {
        $this->doctor = $doctor;
        return $this;
    }
}
