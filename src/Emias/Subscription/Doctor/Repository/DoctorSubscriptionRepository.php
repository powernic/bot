<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\DoctorSubscription;
use Powernic\Bot\Framework\Repository\ServiceEntityRepository;

final class DoctorSubscriptionRepository extends ServiceEntityRepository implements SubscriptionRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctorSubscription::class);
    }
}
