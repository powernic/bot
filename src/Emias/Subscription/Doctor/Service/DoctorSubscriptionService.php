<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\Service;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Powernic\Bot\Emias\API\Entity\ScheduleCollection;
use Powernic\Bot\Emias\API\Repository\ScheduleRepository;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Emias\Exception\SubscriptionExistsException;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Emias\Policy\Repository\PolicyRepository;
use Powernic\Bot\Emias\Repository\DoctorRepository;
use Powernic\Bot\Emias\Repository\SpecialityRepository;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\DoctorSubscription;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\SpecialitySubscription;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\Subscription;
use Powernic\Bot\Emias\Subscription\Doctor\Repository\DoctorSubscriptionRepository;
use Powernic\Bot\Emias\Subscription\EventListener\SubscriptionEventListener;

final class DoctorSubscriptionService extends SubscriptionService
{
    private SpecialityRepository $specialityRepository;
    private PolicyRepository $policyRepository;
    private DoctorRepository $doctorRepository;
    private ScheduleRepository $scheduleRepository;

    public function __construct(
        SpecialityRepository $specialityRepository,
        PolicyRepository $policyRepository,
        EntityManagerInterface $entityManager,
        DoctorRepository $doctorRepository,
        ScheduleRepository $scheduleRepository,
        DoctorSubscriptionRepository $doctorSubscriptionRepository,
        SubscriptionEventListener $subscriptionEventListener
    ) {
        $this->specialityRepository = $specialityRepository;
        $this->policyRepository = $policyRepository;
        $this->doctorRepository = $doctorRepository;
        parent::__construct($doctorSubscriptionRepository, $entityManager, $subscriptionEventListener);
        $this->scheduleRepository = $scheduleRepository;
    }

    public function registerOnAllDaySubscription(int $policyId, int $speciality): SpecialitySubscription
    {
        /** @var Policy $policy */
        $policy = $this->policyRepository->find($policyId);
        /** @var Speciality $speciality */
        $speciality = $this->specialityRepository->find($speciality);
        $specialitySubscription = (new SpecialitySubscription())
            ->setPolicy($policy);

        if ($speciality->specialitySubscriptionsExists($specialitySubscription)) {
            throw new SubscriptionExistsException();
        }

        $speciality->addSpecialitySubscription($specialitySubscription);
        $this->entityManager->persist($speciality);
        $this->entityManager->flush();
        return $specialitySubscription;
    }

    public function registerOnOneDoctorAllDaySubscription(int $policyId, int $doctorId): DoctorSubscription
    {
        $policy = $this->policyRepository->find($policyId);
        $doctor = $this->doctorRepository->find($doctorId);
        $doctorSubscription = (new DoctorSubscription())
            ->setPolicy($policy);
        if ($doctor->doctorSubscriptionsExists($doctorSubscription)) {
            throw new SubscriptionExistsException();
        }
        $doctor->addDoctorSubscription($doctorSubscription);
        $this->entityManager->persist($doctor);
        $this->entityManager->flush();
        return $doctorSubscription;
    }

    public function registerOnAllDoctorOneDaySubscription(
        int $policyId,
        int $speciality,
        DateTime $startTime,
        DateTime $endTime
    ): SpecialitySubscription {
        $policy = $this->policyRepository->find($policyId);
        $speciality = $this->specialityRepository->find($speciality);
        $specialitySubscription = (new SpecialitySubscription())
            ->setPolicy($policy)
            ->setStartTimeInterval($startTime)
            ->setEndTimeInterval($endTime);
        if ($speciality->specialitySubscriptionsExists($specialitySubscription)) {
            throw new SubscriptionExistsException();
        }
        $speciality->addSpecialitySubscription($specialitySubscription);
        $this->entityManager->persist($speciality);
        $this->entityManager->flush();
        return $specialitySubscription;
    }


    protected function getSchedules(DoctorSubscription|Subscription $subscription): ScheduleCollection
    {
        $policy = $subscription->getPolicy();
        $doctor = $subscription->getDoctor();
        return $this->scheduleRepository->findByDoctor($policy, $doctor);
    }
}
