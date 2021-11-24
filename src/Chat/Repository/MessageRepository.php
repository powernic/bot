<?php

namespace Powernic\Bot\Chat\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Powernic\Bot\Chat\Entity\Message;
use Powernic\Bot\Chat\Entity\User;
use Powernic\Bot\Framework\Repository\ServiceEntityRepository;

class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    private function getLastActionCriteria(User $user): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq("user", $user))
            ->andWhere(Criteria::expr()->eq("actionCode", $user->getActionCode()))
            ->andWhere(Criteria::expr()->gte("time", $user->getActionTime()));
    }

    public function countLastAction(User $user): int
    {
        $qb = $this->createQueryBuilder("u");
        $qb->select($qb->expr()->count('u'))
            ->addCriteria($this->getLastActionCriteria($user));

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param User $user
     * @return Message[]
     * @throws QueryException|Exception
     */
    public function getAllByLastAction(User $user): array
    {
        $qb = $this->createQueryBuilder("u");
        $qb->addCriteria($this->getLastActionCriteria($user))->orderBy("u.time", "ASC");

        return $qb->getQuery()->getResult();
    }
}