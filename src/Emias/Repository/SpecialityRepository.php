<?php

namespace Powernic\Bot\Emias\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Powernic\Bot\Emias\Entity\Doctor;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Framework\Repository\ServiceEntityRepository;
/**
 * @method Speciality|null find($id, $lockMode = null, $lockVersion = null)
 * @method Speciality|null findOneBy(array $criteria, array $orderBy = null)
 * @method Speciality[] findAll()
 * @method Speciality[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Speciality::class);
    }
}
