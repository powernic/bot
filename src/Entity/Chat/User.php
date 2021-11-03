<?php

namespace Powernic\Bot\Entity\Chat;

use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Exception;
use Powernic\Bot\Entity\Emias\Policy;

/**
 * @Entity()
 * @Table(name="chat_user")
 **/
class User
{
    /**
     * @Id
     * @Column(type="integer")
     */
    private $id;

    /**
     * @Column(type="string", name="first_name")
     */
    private $firstName;

    /**
     * @Column(type="string", name="last_name")
     */
    private $lastName;

    /**
     * @Column(type="string", name="username", unique="true")
     */
    private $userName;

    /**
     * @Column(type="datetime", name="action_time" )
     */
    private $actionTime;

    /**
     * @Column(type="string", name="action_code" )
     */
    private $actionCode;

    /**
     * @OneToMany(targetEntity=Message::class, mappedBy="user")
     */
    private $messages;

    /**
     * @OneToMany(targetEntity=Powernic\Bot\Entity\Emias\Policy::class, mappedBy="user")
     */
    private $policies;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->policies = new ArrayCollection();
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @param string $lastName
     * @return User
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @param string $userName
     * @return User
     */
    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @return ArrayCollection|Policy[]
     */
    public function getMessages(): ArrayCollection
    {
        return $this->messages;
    }


    public function getMessage(Message $message): self
    {
        return $this;
    }

    public function removeMessage(Message $message): self
    {
        return $this;
    }

    /**
     * @return ArrayCollection|Policy[]
     */
    public function getPolicies(): ArrayCollection
    {
        return $this->policies;
    }

    /**
     * @param DateTime $actionTime
     * @return User
     */
    public function setActionTime(DateTime $actionTime): self
    {
        $this->actionTime = $actionTime;

        return $this;
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getActionTime(): DateTime
    {
        return $this->actionTime;
    }

    /**
     * @param string $actionCode
     * @return User
     */
    public function setActionCode(string $actionCode): self
    {
        $this->actionCode = $actionCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getActionCode(): string
    {
        return $this->actionCode;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
