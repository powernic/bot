<?php

namespace Powernic\Bot\Emias\Entity;

class DoctorInfo
{
    private int $id;
    private int $lpuId;
    private string $name;
    private int $arType;
    private bool $specialityChangeAbility;
    private int $arSpecialityId;
    private string $arSpecialityName;
    private Doctor $mainDoctor;
    /**
     * @var ReceptionType[]
     */
    private array $receptionType = [];
    private array $ldpType = [];
    private array $samplingType = [];
    /**
     * @var Resource[]
     */
    private array $complexResource = [];
    private bool $district;
    private bool $replacement;
    private bool $availableByReferral;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return DoctorInfo
     */
    public function setId(int $id): DoctorInfo
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getLpuId(): int
    {
        return $this->lpuId;
    }

    /**
     * @param int $lpuId
     * @return DoctorInfo
     */
    public function setLpuId(int $lpuId): DoctorInfo
    {
        $this->lpuId = $lpuId;
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
     * @return DoctorInfo
     */
    public function setName(string $name): DoctorInfo
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getArType(): int
    {
        return $this->arType;
    }

    /**
     * @param int $arType
     * @return DoctorInfo
     */
    public function setArType(int $arType): DoctorInfo
    {
        $this->arType = $arType;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSpecialityChangeAbility(): bool
    {
        return $this->specialityChangeAbility;
    }

    /**
     * @param bool $specialityChangeAbility
     * @return DoctorInfo
     */
    public function setSpecialityChangeAbility(bool $specialityChangeAbility): DoctorInfo
    {
        $this->specialityChangeAbility = $specialityChangeAbility;
        return $this;
    }

    /**
     * @return int
     */
    public function getArSpecialityId(): int
    {
        return $this->arSpecialityId;
    }

    /**
     * @param int $arSpecialityId
     * @return DoctorInfo
     */
    public function setArSpecialityId(int $arSpecialityId): DoctorInfo
    {
        $this->arSpecialityId = $arSpecialityId;
        return $this;
    }

    /**
     * @return string
     */
    public function getArSpecialityName(): string
    {
        return $this->arSpecialityName;
    }

    /**
     * @param string $arSpecialityName
     * @return DoctorInfo
     */
    public function setArSpecialityName(string $arSpecialityName): DoctorInfo
    {
        $this->arSpecialityName = $arSpecialityName;
        return $this;
    }

    /**
     * @return Doctor
     */
    public function getMainDoctor(): Doctor
    {
        return $this->mainDoctor;
    }

    /**
     * @param Doctor $mainDoctor
     * @return DoctorInfo
     */
    public function setMainDoctor(Doctor $mainDoctor): DoctorInfo
    {
        $this->mainDoctor = $mainDoctor;
        return $this;
    }

    /**
     * @return ReceptionType[]
     */
    public function getReceptionType(): array
    {
        return $this->receptionType;
    }

    /**
     * @param ReceptionType[] $receptionType
     * @return DoctorInfo
     */
    public function setReceptionType(array $receptionType): DoctorInfo
    {
        $this->receptionType = $receptionType;
        return $this;
    }

    /**
     * @return array
     */
    public function getLdpType(): array
    {
        return $this->ldpType;
    }

    /**
     * @param array $ldpType
     * @return DoctorInfo
     */
    public function setLdpType(array $ldpType): DoctorInfo
    {
        $this->ldpType = $ldpType;
        return $this;
    }

    /**
     * @return array
     */
    public function getSamplingType(): array
    {
        return $this->samplingType;
    }

    /**
     * @param array $samplingType
     * @return DoctorInfo
     */
    public function setSamplingType(array $samplingType): DoctorInfo
    {
        $this->samplingType = $samplingType;
        return $this;
    }

    /**
     * @return Resource[]
     */
    public function getComplexResource(): array
    {
        return $this->complexResource;
    }

    /**
     * @param Resource[] $complexResource
     * @return DoctorInfo
     */
    public function setComplexResource(array $complexResource): DoctorInfo
    {
        $this->complexResource = $complexResource;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDistrict(): bool
    {
        return $this->district;
    }

    /**
     * @param bool $district
     * @return DoctorInfo
     */
    public function setDistrict(bool $district): DoctorInfo
    {
        $this->district = $district;
        return $this;
    }

    /**
     * @return bool
     */
    public function isReplacement(): bool
    {
        return $this->replacement;
    }

    /**
     * @param bool $replacement
     * @return DoctorInfo
     */
    public function setReplacement(bool $replacement): DoctorInfo
    {
        $this->replacement = $replacement;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAvailableByReferral(): bool
    {
        return $this->availableByReferral;
    }

    /**
     * @param bool $availableByReferral
     * @return DoctorInfo
     */
    public function setAvailableByReferral(bool $availableByReferral): DoctorInfo
    {
        $this->availableByReferral = $availableByReferral;
        return $this;
    }

}
