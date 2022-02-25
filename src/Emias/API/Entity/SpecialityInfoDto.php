<?php

namespace Powernic\Bot\Emias\API\Entity;

class SpecialityInfoDto
{
    public int $code;
    public string $name;
    public bool $male;
    public bool $female;
    public array $areaType;
    public bool $therapeutic;
    public bool $isMultipleLpuSpeciality;
}
