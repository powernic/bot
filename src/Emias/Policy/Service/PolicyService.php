<?php

namespace Powernic\Bot\Emias\Policy\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Powernic\Bot\Chat\Repository\MessageRepository;
use Powernic\Bot\Chat\Repository\UserRepository;
use Powernic\Bot\Emias\Exception\PolicyNotFound;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Emias\Policy\Repository\PolicyRepository;
use Powernic\Bot\Emias\API\Repository\PolicyRepository as ApiPolicyRepository;

final class PolicyService
{

    private MessageRepository $messageRepository;
    private UserRepository $userRepository;
    private EntityManager $entityManager;
    private PolicyRepository $policyRepository;
    private ApiPolicyRepository $apiPolicyRepository;

    public function __construct(
        EntityManager $entityManager,
        MessageRepository $messageRepository,
        UserRepository $userRepository,
        PolicyRepository $policyRepository,
        ApiPolicyRepository $apiPolicyRepository
    ) {
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->policyRepository = $policyRepository;
        $this->apiPolicyRepository = $apiPolicyRepository;
    }

    /**
     * @param int $userId
     * @param string $date
     * @return Policy
     * @throws PolicyNotFound
     */
    public function addPolicy(int $userId, string $date): Policy
    {
        $user = $this->userRepository->find($userId);
        $messages = $this->messageRepository->getAllByLastAction($user);
        $name = $messages[0]->getText();
        $code = $messages[1]->getText();
        $policy = (new Policy())
            ->setUser($user)
            ->setName($name)
            ->setCode($code)
            ->setDate($date);
        if (!$this->isValidPolice($policy)) {
            throw new PolicyNotFound();
        }
        $this->entityManager->persist($policy);
        $this->entityManager->flush();
        return $policy;
    }

    private function isValidPolice(Policy $policy): bool
    {
        $policy = $this->apiPolicyRepository->find($policy);
        if ($policy) {
            return true;
        }
        return false;
    }


    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws PolicyNotFound
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function editPolicy(int $policyId, int $userId, string $date): Policy
    {
        $user = $this->userRepository->find($userId);
        $messages = $this->messageRepository->getAllByLastAction($user);
        $policy = $this->policyRepository->find($policyId);
        $name = $messages[0]->getText();
        $code = $messages[1]->getText();
        $policy
            ->setName($name)
            ->setCode($code)
            ->setDate($date);
        if (!$this->isValidPolice($policy)) {
            throw new PolicyNotFound();
        }
        $this->entityManager->persist($policy);
        $this->entityManager->flush();

        return $policy;
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \Doctrine\ORM\ORMException
     */
    public function removePolicy(int $policyId, int $userId): Policy
    {
        $policy = $this->policyRepository->findOneBy(['id' => $policyId, 'user' => $userId]);
        if (!$policy) {
            throw new EntityNotFoundException();
        }
        $this->entityManager->remove($policy);
        $this->entityManager->flush();

        return $policy;
    }
}
