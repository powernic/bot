<?php

namespace Powernic\Bot\Emias\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Powernic\Bot\Emias\Entity\Doctor;
use Powernic\Bot\Framework\Repository\ServiceEntityRepository;

/**
 * @method Doctor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Doctor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Doctor[] findAll()
 * @method Doctor[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Doctor::class);
    }
}
