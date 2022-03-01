<?php

namespace Powernic\Bot\Emias\API\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Generator;

/**
 * @method bool add(Speciality $element)
 * @method Speciality[] getIterator()
 */
class SpecialityCollection extends ArrayCollection
{

    public function getByCode(int $code): ?Speciality
    {
        return $this->get($code);
    }

    /**
     * @return int[]
     * @throws \Exception
     */
    public function getCodes(): array
    {
        $codes = [];
        foreach ($this->getIterator() as $speciality) {
            $codes[] = $speciality->getCode();
        }
        return $codes;
    }

    /**
     * @param SpecialityInfoDto[] $specialityDtoCollection
     * @return self
     */
    public static function createFromArrayDto(array $specialityDtoCollection): self
    {
        $specialityCollection = new self();
        foreach ($specialityDtoCollection as $specialityDto) {
            $speciality = new Speciality($specialityDto->name, $specialityDto->code);
            $specialityCollection->set($specialityDto->code, $speciality);
        }
        return $specialityCollection;
    }
}
