<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\SpecialitySubscription;
use Powernic\Bot\Framework\Repository\ServiceEntityRepository;

final class SpecialitySubscriptionRepository extends ServiceEntityRepository  implements SubscriptionRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpecialitySubscription::class);
    }
}
