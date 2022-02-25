<?php

namespace Powernic\Bot\Emias\API\Entity;

class DoctorInfoDto
{

    public int $id;
    public int $lpuId;
    public string $name;
    public int $arType;
    public bool $specialityChangeAbility;
    public int $arSpecialityId;
    public string $arSpecialityName;
    public DoctorDto $mainDoctor;
    /**
     * @var ReceptionTypeDto[]
     */
    public array $receptionType = [];
    public array $ldpType = [];
    public array $samplingType = [];
    /**
     * @var ResourceDto[]
     */
    public array $complexResource = [];
    public bool $district;
    public bool $replacement;
    public bool $availableByReferral;
}
