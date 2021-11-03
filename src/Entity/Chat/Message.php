<?php

namespace Powernic\Bot\Entity\Chat;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity(repositoryClass="Powernic\Bot\Repository\Chat\MessageRepository")
 * @Table(name="chat_message")
 **/
class Message
{
    /**
     * @Id
     * @Column(type="integer")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity=User::class, inversedBy="messages")
     */
    private $user;

    /**
     * @Column(type="string", length=4096)
     */
    private $text;

    /**
     * @Column(type="string", name="action_code" )
     */
    private $actionCode;

    /**
     * @Column(type="datetime")
     */
    private $time;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param DateTime $time
     * @return Message
     */
    public function setTime(DateTime $time): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @param string $actionCode
     * @return Message
     */
    public function setActionCode(string $actionCode): self
    {
        $this->actionCode = $actionCode;

        return $this;
    }

    /**
     * @param string $text
     * @return Message
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param int $id
     * @return Message
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param User $user
     * @return Message
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}