<?php

namespace Powernic\Bot\Repository\Chat;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\QueryException;
use Exception;
use Powernic\Bot\Entity\Chat\Message;
use Powernic\Bot\Entity\Chat\User;

class MessageRepository extends EntityRepository
{

    /**
     * @throws Exception
     */
    private function getLastActionCriteria(User $user): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq("user", $user))
            ->andWhere(Criteria::expr()->eq("actionCode", $user->getActionCode()))
            ->andWhere(Criteria::expr()->gte("time", $user->getActionTime()));
    }

    /**
     * @throws Exception
     */
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