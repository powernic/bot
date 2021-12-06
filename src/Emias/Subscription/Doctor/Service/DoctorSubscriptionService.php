<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\Service;

use Doctrine\ORM\EntityManager;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Emias\Policy\Repository\PolicyRepository;
use Powernic\Bot\Emias\Repository\SpecialityRepository;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\SpecialitySubscription;

final class DoctorSubscriptionService
{
    private SpecialityRepository $specialityRepository;
    private PolicyRepository $policyRepository;
    private EntityManager $entityManager;

    public function __construct(
        SpecialityRepository $specialityRepository,
        PolicyRepository $policyRepository,
        EntityManager $entityManager
    ) {
        $this->specialityRepository = $specialityRepository;
        $this->policyRepository = $policyRepository;
        $this->entityManager = $entityManager;
    }

    public function registerOnAllDaySubscription(int $policyId, int $speciality)
    {
        /** @var Policy $policy */
        $policy = $this->policyRepository->find($policyId);
        /** @var Speciality $speciality */
        $speciality = $this->specialityRepository->find($speciality);
        $doctorSubscription = (new SpecialitySubscription())
            ->setPolicy($policy);
        $speciality->addSpecialitySubscription($doctorSubscription);
        $this->entityManager->persist($speciality);
        $this->entityManager->flush();
    }
}
