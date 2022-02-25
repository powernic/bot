<?php

namespace Powernic\Bot\Emias\API\Entity;

class PersonName
{
    private string $firstName;
    private string $lastName;
    private string $secondName;

    public function __construct(string $firstName, string $lastName, string $secondName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->secondName = $secondName;
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

    /**
     * @return string
     */
    public function getSecondName(): string
    {
        return $this->secondName;
    }
    
    
}
