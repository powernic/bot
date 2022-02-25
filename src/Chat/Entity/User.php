<?php

namespace Powernic\Bot\Chat\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Doctrine\ORM\Mapping AS ORM;

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
    private int $id;

    /**
     * @Column(type="string", name="account_name", unique="true")
     */
    private string $accountName;

    /**
     * @Embedded(class="UserName" , columnPrefix = false )
     */
    private UserName $userName;

    /**
     * @Embedded(class="Action" , columnPrefix = false )
     */
    private Action $action;

    /**
     * @OneToMany(targetEntity=Message::class, mappedBy="user")
     */
    private $messages;

    /**
     * @OneToMany(targetEntity=\Powernic\Bot\Emias\Policy\Entity\Policy::class, mappedBy="user")
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
     * @param string $accountName
     * @return User
     */
    public function setAccountName(string $accountName): self
    {
        $this->accountName = $accountName;

        return $this;
    }
    /**
     * @return string
     */
    public function getAccountName(): string
    {
        return $this->accountName;
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return UserName
     */
    public function getUserName(): UserName
    {
        return $this->userName;
    }

    /**
     * @param UserName $userName
     * @return User
     */
    public function setUserName(UserName $userName): self
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @return Action
     */
    public function getAction(): Action
    {
        return $this->action;
    }

    /**
     * @param Action $action
     * @return User
     */
    public function setAction(Action $action): self
    {
        $this->action = $action;
        return $this;
    }

}
