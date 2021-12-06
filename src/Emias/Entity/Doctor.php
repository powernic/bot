<?php

namespace Powernic\Bot\Emias\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @Entity()
 * @Table(name="emias_doctor")
 **/
class Doctor
{
    /**
     * @var string
     * @Column(type="string")
     */
    private string $specialityName;

    private int $specialityId;

    /**
     * @ManyToOne(targetEntity=\Powernic\Bot\Emias\Entity\Speciality::class, inversedBy="doctors")
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
    private int $mejiId;
    /**
     * @Id()
     * @var int
     * @Column(type="integer")
     */
    private int $employeeId;

    /**
     * @return string
     */
    public function getSpecialityName(): string
    {
        return $this->specialityName;
    }

    /**
     * @param string $specialityName
     * @return Doctor
     */
    public function setSpecialityName(string $specialityName): Doctor
    {
        $this->specialityName = $specialityName;
        return $this;
    }

    /**
     * @return int
     */
    public function getSpecialityId(): int
    {
        return $this->specialityId;
    }

    /**
     * @param int $specialityId
     * @return Doctor
     */
    public function setSpecialityId(int $specialityId): Doctor
    {
        $this->specialityId = $specialityId;
        return $this;
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
     * @return int
     */
    public function getMejiId(): int
    {
        return $this->mejiId;
    }

    /**
     * @param int $mejiId
     * @return Doctor
     */
    public function setMejiId(int $mejiId): Doctor
    {
        $this->mejiId = $mejiId;
        return $this;
    }

    /**
     * @return int
     */
    public function getEmployeeId(): int
    {
        return $this->employeeId;
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

}
