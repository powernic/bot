<?php

namespace Powernic\Bot\Emias\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use phpDocumentor\Reflection\Types\Integer;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\DoctorSubscription;

/**
 * @Entity()
 * @Table(name="emias_doctor")
 **/
class Doctor
{

    /**
     * @ManyToOne(targetEntity=\Powernic\Bot\Emias\Entity\Speciality::class, inversedBy="doctors", cascade={"persist",
     *     "remove"})
     * @JoinColumn(nullable=true , referencedColumnName="code")
     */
    private ?Speciality $speciality;

    /**
     * @var string
     * @Column(type="string")
     */
    private string $firstName;
    /**
     * @var string
     * @Column(type="string")
     */
    private string $lastName;
    /**
     * @var string
     * @Column(type="string")
     */
    private string $secondName;
    /**
     * @Id()
     * @var int
     * @Column(type="bigint")
     */
    private int $employeeId;

    /**
     * @OneToMany(targetEntity=\Powernic\Bot\Emias\Subscription\Doctor\Entity\DoctorSubscription::class,
     *     mappedBy="doctor", cascade={"persist" })
     */
    private $doctorSubscriptions;

    public function __construct(int $employeeId)
    {
        $this->doctorSubscriptions = new ArrayCollection();
        $this->employeeId = $employeeId;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return Doctor
     */
    public function setFirstName(string $firstName): Doctor
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return Doctor
     */
    public function setLastName(string $lastName): Doctor
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecondName(): string
    {
        return $this->secondName;
    }

    /**
     * @param string $secondName
     * @return Doctor
     */
    public function setSecondName(string $secondName): Doctor
    {
        $this->secondName = $secondName;
        return $this;
    }

    /**
     * @param int $employeeId
     * @return Doctor
     */
    public function setEmployeeId(int $employeeId): Doctor
    {
        $this->employeeId = $employeeId;
        return $this;
    }

    /**
     * @return ?Speciality
     */
    public function getSpeciality(): ?Speciality
    {
        return $this->speciality;
    }

    /**
     * @param ?Speciality $speciality
     */
    public function setSpeciality(?Speciality $speciality): void
    {
        $this->speciality = $speciality;
    }

    /**
     * @param DoctorSubscription $doctorSubscription
     * @return Doctor
     */
    public function addDoctorSubscription(DoctorSubscription $doctorSubscription): self
    {
        if (!$this->doctorSubscriptions->contains($doctorSubscription)) {
            $this->doctorSubscriptions[] = $doctorSubscription;
            $doctorSubscription->setDoctor($this);
        }
        return $this;
    }

    public function doctorSubscriptionsExists(DoctorSubscription $doctorSubscription): bool
    {
        return $this->doctorSubscriptions->exists(
            function ($key, DoctorSubscription $value) use ($doctorSubscription) {
                $theSamePolicy = $value->getPolicy()->getId() === $doctorSubscription->getPolicy()->getId();
                $theSameStartTime = $value->getStartTimeInterval() === $doctorSubscription->getStartTimeInterval();
                $theSameEndTime = $value->getEndTimeInterval() === $doctorSubscription->getEndTimeInterval();
                $theSameTimeInterval = $theSameStartTime && $theSameEndTime;
                return $theSamePolicy && $theSameTimeInterval;
            }
        );
    }

    /**
     * @param DoctorSubscription $doctorSubscription
     * @return Doctor
     */
    public function removeDoctorSubscription(DoctorSubscription $doctorSubscription): self
    {
        if (!$this->doctorSubscriptions->removeElement($doctorSubscription)) {
            if ($doctorSubscription->getDoctor() === $this) {
                $doctorSubscription->setDoctor(null);
            }
        }
        return $this;
    }
}
