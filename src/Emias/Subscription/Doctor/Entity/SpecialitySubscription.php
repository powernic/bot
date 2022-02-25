<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Powernic\Bot\Emias\Entity\Speciality;

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
