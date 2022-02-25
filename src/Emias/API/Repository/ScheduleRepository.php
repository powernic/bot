<?php

namespace Powernic\Bot\Emias\API\Repository;

use Powernic\Bot\Emias\API\Entity\DoctorCollection;
use Powernic\Bot\Emias\API\Entity\ScheduleCollection;
use Powernic\Bot\Emias\Entity\Doctor;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Emias\Policy\Entity\Policy;

class ScheduleRepository extends EmiasRepository
{
    /**
     * @param Policy $policy
     * @param Speciality $speciality
     * @return ScheduleCollection
     */
    public function findBySpeciality(Policy $policy, Speciality $speciality): ScheduleCollection
    {
        $scheduleInfoDtoCollection = $this->apiService->getBatchScheduleInfo($policy, $speciality);
        return ScheduleCollection::createFromArrayDto($scheduleInfoDtoCollection);
    }

    /**
     * @param Policy $policy
     * @param Doctor $doctor
     * @return ScheduleCollection
     */
    public function findByDoctor(Policy $policy, Doctor $doctor): ScheduleCollection
    {
        $speciality = $doctor->getSpeciality();
        $doctorInfoDtoCollection = $this->apiService->getDoctorsInfoCollection($policy, $speciality->getCode());
        $doctorCollection = DoctorCollection::createFromArrayDto($doctorInfoDtoCollection);
        $emiasDoctor = $doctorCollection->getByEmployeeId($doctor->getEmployeeId());
        if ($emiasDoctor && $emiasDoctor->isAvailable()) {
            $availableResourceScheduleInfoDto = $this->apiService->getAvailableResourceScheduleInfo(
                $policy,
                $emiasDoctor
            );
            return ScheduleCollection::createFromAvailableResourceScheduleDto($availableResourceScheduleInfoDto);
        }
        return new ScheduleCollection();
    }
}
