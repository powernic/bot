<?php

namespace Powernic\Bot\Chat\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Powernic\Bot\Chat\Entity\User;
use Powernic\Bot\Framework\Repository\ServiceEntityRepository;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}
