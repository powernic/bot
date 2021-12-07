<?php

namespace Powernic\Bot\Emias\Policy\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Exception;
use Powernic\Bot\Chat\Entity\User;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\DoctorSubscription;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\SpecialitySubscription;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Entity()
 * @Table(name="emias_policy")
 **/
class Policy
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @Column(type="string")
     */
    #[Assert\Length(
        min: 16,
        max: 16,
        exactMessage: "validator.policy.code"
    )]
    private $code;

    /**
     * @Column(type="date")
     */
    #[Assert\Date(
        message: "validator.policy.date"
    )]
    private $date;

    /**
     * @Column(type="string")
     */
    private $name;

    /**
     * @ManyToOne(targetEntity=\Powernic\Bot\Chat\Entity\User::class, inversedBy="policies")
     */
    private $user;

    /**
     * @OneToMany(targetEntity=\Powernic\Bot\Emias\Subscription\Doctor\Entity\SpecialitySubscription::class, mappedBy="policy")
     */
    private $specialitySubscriptions;

    /**
     * @OneToMany(targetEntity=\Powernic\Bot\Emias\Subscription\Doctor\Entity\DoctorSubscription::class, mappedBy="policy")
     */
    private $doctorSubscriptions;

    public function __construct()
    {
        $this->specialitySubscriptions = new ArrayCollection();
        $this->doctorSubscriptions = new ArrayCollection();
    }

    /**
     * @param string $code
     * @return Policy
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param string $date
     * @return Policy
     * @throws Exception
     */
    public function setDate(string $date): self
    {
        $this->date = new DateTime($date);

        return $this;
    }

    /**
     * @param string $name
     * @return Policy
     */
    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Policy
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return ArrayCollection|SpecialitySubscription[]
     */
    public function getSpecialitySubscriptions(): ArrayCollection
    {
        return $this->specialitySubscriptions;
    }
    /**
     * @return ArrayCollection|DoctorSubscription[]
     */
    public function getDoctorSubscriptions(): ArrayCollection
    {
        return $this->doctorSubscriptions;
    }
}
