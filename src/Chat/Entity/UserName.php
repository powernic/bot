<?php

namespace Powernic\Bot\Chat\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

/** @Embeddable */
class UserName
{
    /**
     * @Column(type="string", name="first_name")
     */
    private string $firstName;

    /**
     * @Column(type="string", name="last_name")
     */
    private string $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
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
}
