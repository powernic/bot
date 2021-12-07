<?php

namespace Powernic\Bot\Emias\Service;

use Doctrine\ORM\EntityManagerInterface;
use Powernic\Bot\Emias\Entity\DoctorInfo;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Emias\Repository\DoctorRepository;
use Powernic\Bot\Emias\Repository\SpecialityRepository;

class DoctorService
{

    private DoctorRepository $doctorRepository;
    private EntityManagerInterface $entityManager;
    private SpecialityRepository $specialityRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        DoctorRepository $doctorRepository,
        SpecialityRepository $specialityRepository
    ) {
        $this->doctorRepository = $doctorRepository;
        $this->entityManager = $entityManager;
        $this->specialityRepository = $specialityRepository;
    }

    /**
     * @param DoctorInfo[] $doctorInfoCollection
     */
    public function saveDoctors(array $doctorInfoCollection, int $specialityId)
    {
        $speciality = $this->specialityRepository->find($specialityId);
        foreach ($doctorInfoCollection as $doctorInfo) {
            $doctor = $doctorInfo->getMainDoctor();
            $doctor->setSpeciality($speciality);
            $doctorEntity = $this->doctorRepository->find($doctor->getEmployeeId());
            if ($doctorEntity) {
                $doctorEntity
                    ->setSpecialityName($doctor->getSpecialityName())
                    ->setFirstName($doctor->getFirstName())
                    ->setLastName($doctor->getLastName())
                    ->setSecondName($doctor->getSecondName())
                    ->setSpeciality($doctor->getSpeciality());
            } else {
                $this->entityManager->persist($doctor);
            }
        }

        $this->entityManager->flush();
    }
}
