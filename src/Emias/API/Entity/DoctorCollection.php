<?php

namespace Powernic\Bot\Emias\API\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Traversable;

/**
 * @method bool add(Doctor $element)
 * @method Doctor[]|Traversable getIterator()
 */
class DoctorCollection extends ArrayCollection
{
    public function sortByAvailable(): self
    {
        $collection = $this->getIterator();
        $collection->uasort(
            fn(Doctor $doctor) => ($doctor->isAvailable() == true) ? -1 : 1
        );
        return new self($collection->getArrayCopy());
    }

    public function getByEmployeeId(int $employeeId): ?Doctor
    {
        return $this->get($employeeId);
    }

    /**
     * @param DoctorInfoDto[] $doctorDtoCollection
     * @return self
     */
    public static function createFromArrayDto(array $doctorDtoCollection): self
    {
        $doctorCollection = new self();
        foreach ($doctorDtoCollection as $doctorDto) {
            $mainDoctor = $doctorDto->mainDoctor;
            $employeeId = $mainDoctor->employeeId;
            $availableResourceId = $doctorDto->id;
            $complexResource = reset($doctorDto->complexResource);
            $complexResourceId = !empty($complexResource) ? $complexResource->id : null;
            $isAvailable = !empty($doctorDto->complexResource);
            $personName = new PersonName($mainDoctor->firstName, $mainDoctor->lastName, $mainDoctor->secondName);
            $doctor = new Doctor($employeeId, $personName, $availableResourceId, $isAvailable, $complexResourceId);
            $doctorWasAdded = $doctorCollection->containsKey($doctor->getEmployeeId());
            if (($doctorWasAdded && $doctor->isAvailable()) || !$doctorWasAdded) {
                $doctorCollection->set($doctor->getEmployeeId(), $doctor);
            }
        }
        return $doctorCollection;
    }
}
