<?php

namespace Powernic\Bot\Emias\Service;

use Doctrine\ORM\EntityManagerInterface;
use Powernic\Bot\Emias\API\Entity\DoctorCollection;
use Powernic\Bot\Emias\Entity\Doctor;
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
     * @param DoctorCollection $doctorCollection
     * @param int $specialityId
     */
    public function saveDoctors(DoctorCollection $doctorCollection, int $specialityId)
    {
        $speciality = $this->specialityRepository->find($specialityId);
        foreach ($doctorCollection as $doctor) {
            $doctorEntity = $this->doctorRepository->find($doctor->getEmployeeId());
            if (!$doctorEntity) {
                $doctorEntity = new Doctor($doctor->getEmployeeId());
            }
            $personName = $doctor->getPersonName();
            $doctorEntity
                ->setFirstName($personName->getFirstName())
                ->setLastName($personName->getLastName())
                ->setSecondName($personName->getSecondName())
                ->setSpeciality($speciality);
            $this->entityManager->persist($doctorEntity);
        }

        $this->entityManager->flush();
    }
}
