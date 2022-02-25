<?php

namespace Powernic\Bot\Emias\API\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Powernic\Bot\Emias\Entity\Schedule;

/**
 * @method bool add(Speciality $element)
 * @method Speciality[] getIterator()
 */
class SpecialityCollection extends ArrayCollection
{
    /**
     * @param SpecialityInfoDto[] $specialityDtoCollection
     * @return self
     */
    public static function createFromArrayDto(array $specialityDtoCollection): self
    {
        $specialityCollection = new self();
        foreach ($specialityDtoCollection as $specialityDto) {
            $speciality = new Speciality($specialityDto->name, $specialityDto->code);
            $specialityCollection->add($speciality);
        }
        return $specialityCollection;
    }
}
