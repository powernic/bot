<?php

namespace Powernic\Bot\Emias\API\Entity;

class Doctor
{
    private int $employeeId;
    private bool $isAvailable;
    private int $availableResourceId;
    private ?int $complexResourceId;
    private PersonName $personName;

    public function __construct(
        int $employeeId,
        PersonName $personName,
        int $availableResourceId,
        bool $isAvailable,
        ?int $complexResourceId = null,
    ) {
        $this->employeeId = $employeeId;
        $this->isAvailable = $isAvailable;
        $this->availableResourceId = $availableResourceId;
        $this->complexResourceId = $complexResourceId;
        $this->personName = $personName;
    }

    /**
     * @return int
     */
    public function getEmployeeId(): int
    {
        return $this->employeeId;
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function getAvailableResourceId(): int
    {
        return $this->availableResourceId;
    }

    public function getComplexResourceId(): ?int
    {
        return $this->complexResourceId;
    }

    /**
     * @return PersonName
     */
    public function getPersonName(): PersonName
    {
        return $this->personName;
    }

    public function getFullName(): string
    {
        return $this->personName->getLastName() . " " . $this->personName->getFirstName() . " " .
            $this->personName->getSecondName();
    }

    public function getAvailableMark(): string
    {
        return $this->isAvailable() ? "✔" : "❌";
    }
}
