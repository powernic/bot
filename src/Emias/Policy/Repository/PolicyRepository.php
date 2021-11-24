<?php

namespace Powernic\Bot\Emias\Policy\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Framework\Repository\ServiceEntityRepository;

final class PolicyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Policy::class);
    }
}
