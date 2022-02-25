<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\Service;

use Doctrine\ORM\EntityManagerInterface;
use Powernic\Bot\Emias\API\Entity\ScheduleCollection;
use Powernic\Bot\Emias\API\Repository\ScheduleRepository;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\Subscription;
use Powernic\Bot\Emias\Subscription\Doctor\Repository\SpecialitySubscriptionRepository;
use Powernic\Bot\Emias\Subscription\EventListener\SubscriptionEventListener;

class SpecialitySubscriptionService extends SubscriptionService
{
    private ScheduleRepository $scheduleRepository;

    public function __construct(
        SpecialitySubscriptionRepository $subscriptionRepository,
        EntityManagerInterface $entityManager,
        SubscriptionEventListener $subscriptionEventListener,
        ScheduleRepository $scheduleRepository
    ) {
        parent::__construct($subscriptionRepository, $entityManager, $subscriptionEventListener);
        $this->scheduleRepository = $scheduleRepository;
    }

    protected function getSchedules(Subscription $subscription): ScheduleCollection
    {
        $policy = $subscription->getPolicy();
        $speciality = $subscription->getSpeciality();
        return $this->scheduleRepository->findBySpeciality($policy, $speciality);
    }

}
