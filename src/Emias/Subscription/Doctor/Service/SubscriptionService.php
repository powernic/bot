<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\Service;

use Doctrine\ORM\EntityManagerInterface;
use Powernic\Bot\Emias\API\Entity\ScheduleCollection;
use Powernic\Bot\Emias\Entity\Schedule;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\SpecialitySubscription;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\Subscription;
use Powernic\Bot\Emias\Subscription\Doctor\Repository\SubscriptionRepositoryInterface;
use Powernic\Bot\Emias\Subscription\EventListener\SubscriptionEventListener;

abstract class SubscriptionService
{
    private SubscriptionRepositoryInterface $subscriptionRepository;
    protected EntityManagerInterface $entityManager;
    private SubscriptionEventListener $subscriptionEventListener;

    public function __construct(
        SubscriptionRepositoryInterface $subscriptionRepository,
        EntityManagerInterface $entityManager,
        SubscriptionEventListener $subscriptionEventListener
    ) {

        $this->subscriptionRepository = $subscriptionRepository;
        $this->entityManager = $entityManager;
        $this->subscriptionEventListener = $subscriptionEventListener;
    }


    public function processNearestSchedule()
    {
        $subscriptions = $this->subscriptionRepository->findAll();
        foreach ($subscriptions as $subscription) {
            $nearestSchedule = $this->getNearestSchedule($subscription);
            $savedSchedule = $subscription->getSchedule();
            if ($nearestSchedule === null) {
                $this->subscriptionEventListener->onNotAvailableSchedule($subscription);
            } elseif ($this->isNewestNearestSchedule($savedSchedule, $nearestSchedule)) {
                $this->subscriptionEventListener->onNewestNearestSchedule($subscription, $nearestSchedule);
            }
        }
        $this->entityManager->flush();
    }

    abstract protected function getSchedules(Subscription $subscription): ScheduleCollection;

    private function getNearestScheduleInConcreteDay(Subscription $subscription): ?Schedule
    {
        $targetDate = $subscription->getStartTimeInterval();
        return $this->getSchedules($subscription)->getNearestInConcreteDay($targetDate);
    }

    private function getNearestScheduleInAllDay(Subscription $subscription): ?Schedule
    {
        return $this->getSchedules($subscription)->getNearestInAllDay();
    }

    private function getNearestSchedule(Subscription $subscription): ?Schedule
    {
        if ($subscription->hasTargetTimeInterval()) {
            return $this->getNearestScheduleInConcreteDay($subscription);
        } else {
            return $this->getNearestScheduleInAllDay($subscription);
        }
    }

    /**
     * @param ?Schedule $savedSchedule
     * @param Schedule|null $nearestSchedule
     * @return bool
     */
    protected function isNewestNearestSchedule(?Schedule $savedSchedule, ?Schedule $nearestSchedule): bool
    {
        $isNewNearestSchedule = true;
        if ($savedSchedule) {
            $isNewNearestSchedule = $nearestSchedule->getStartTime() < $savedSchedule->getStartTime();
        }
        return $isNewNearestSchedule;
    }
}
