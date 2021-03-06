<?php

namespace Powernic\Bot\Emias\Policy\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Framework\Repository\ServiceEntityRepository;

/**
 * @method Policy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Policy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Policy[] findAll()
 * @method Policy[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class PolicyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Policy::class);
    }

    /**
     * @param int $userId
     * @return Policy[]
     */
    public function findByUserId(int $userId): array
    {
        return $this->findBy(['user' => $userId]);
    }
}
