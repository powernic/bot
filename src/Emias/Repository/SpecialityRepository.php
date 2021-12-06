<?php

namespace Powernic\Bot\Emias\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Framework\Repository\ServiceEntityRepository;

class SpecialityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Speciality::class);
    }
}
