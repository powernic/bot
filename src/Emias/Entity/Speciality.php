<?php

namespace Powernic\Bot\Emias\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\SpecialitySubscription;

/**
 * @Entity()
 * @Table(name="emias_speciality")
 **/
class Speciality
{

    /**
     * @Id()
     * @var int
     * @Column(type="integer")
     */
    private int $code;
    /**
     * @var string
     * @Column(type="string")
     */
    private string $name;

    /**
     * @OneToMany(targetEntity=\Powernic\Bot\Emias\Subscription\Doctor\Entity\SpecialitySubscription::class,
     *     mappedBy="speciality", cascade={"persist" })
     *
     */
    private $specialitySubscriptions;

    /**
     * @OneToMany(targetEntity=\Powernic\Bot\Emias\Entity\Doctor::class,
     *     mappedBy="speciality", cascade={"persist"})
     * @var Doctor[]
     */
    private $doctors;

    public function __construct()
    {
        $this->specialitySubscriptions = new ArrayCollection();
        $this->doctors = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return self
     */
    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
 

    /**
     * @param SpecialitySubscription $specialitySubscription
     * @return $this
     */
    public function addSpecialitySubscription(SpecialitySubscription $specialitySubscription): self
    {
        if (!$this->specialitySubscriptions->contains($specialitySubscription)) {
            $this->specialitySubscriptions[] = $specialitySubscription;
            $specialitySubscription->setSpeciality($this);
        }

        return $this;
    }

    /**
     * @param SpecialitySubscription $specialitySubscription
     * @return $this
     */
    public function removeSpecialitySubscription(SpecialitySubscription $specialitySubscription): self
    {
        if (!$this->specialitySubscriptions->removeElement($specialitySubscription)) {
            if ($specialitySubscription->getSpeciality() === $this) {
                $specialitySubscription->setSpeciality(null);
            }
        }

        return $this;
    }

    public function specialitySubscriptionsExists(SpecialitySubscription $specialitySubscription)
    {
        return $this->specialitySubscriptions->exists(
            function ($key, SpecialitySubscription $value) use ($specialitySubscription) {
                $theSamePolicy = $value->getPolicy()->getId() === $specialitySubscription->getPolicy()->getId();
                $theSameStartTime = $value->getStartTimeInterval()?->getTimestamp(
                    ) === $specialitySubscription->getStartTimeInterval()?->getTimestamp();
                $theSameEndTime = $value->getEndTimeInterval()?->getTimestamp(
                    ) === $specialitySubscription->getEndTimeInterval()?->getTimestamp();
                $theSameTimeInterval = $theSameStartTime && $theSameEndTime;
                return $theSamePolicy && $theSameTimeInterval;
            }
        );
    }
}
