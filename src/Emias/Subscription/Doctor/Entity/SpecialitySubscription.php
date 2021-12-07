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
class SpecialitySubscription extends Subscription
{

    /**
     * @ManyToOne(targetEntity=\Powernic\Bot\Emias\Entity\Speciality::class, inversedBy="specialitySubscriptions")
     * @JoinColumn(nullable=true , referencedColumnName="code")
     */
    protected ?Speciality $speciality;

    /**
     * @OneToOne(targetEntity=\Powernic\Bot\Emias\Entity\ScheduleInfo::class, inversedBy="specialitySubscription",
     *     cascade={"persist", "remove"})
     */
    protected ?ScheduleInfo $scheduleInfo;

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

}
