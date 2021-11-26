<?php

namespace Powernic\Bot\Emias\Policy\Service;

use Doctrine\ORM\EntityManager;
use Exception;
use Powernic\Bot\Chat\Repository\MessageRepository;
use Powernic\Bot\Chat\Repository\UserRepository;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Emias\Policy\Repository\PolicyRepository;

final class PolicyService
{

    private MessageRepository $messageRepository;
    private UserRepository $userRepository;
    private EntityManager $entityManager;
    private PolicyRepository $policyRepository;

    public function __construct(
        EntityManager $entityManager,
        MessageRepository $messageRepository,
        UserRepository $userRepository,
        PolicyRepository $policyRepository,
    ) {
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->policyRepository = $policyRepository;
    }

    /**
     * @param int $userId
     * @param string $date
     * @return Policy
     * @throws Exception
     */
    public function addPolicy(int $userId, string $date): Policy
    {
        try {
            $user = $this->userRepository->find($userId);
            $messages = $this->messageRepository->getAllByLastAction($user);
            $name = $messages[0]->getText();
            $code = $messages[1]->getText();
            $policy = (new Policy())
                ->setUser($user)
                ->setName($name)
                ->setCode($code)
                ->setDate($date);
            $this->entityManager->persist($policy);

            return $policy;
        } catch (Exception) {
            throw new Exception("exception.policy.add.policy");
        }
    }

    /**
     * @throws Exception
     */
    public function editPolicy(int $policyId, int $userId, string $date){
        try {
            $user = $this->userRepository->find($userId);
            $messages = $this->messageRepository->getAllByLastAction($user);
            $policy = $this->policyRepository->find($policyId);
            $name = $messages[0]->getText();
            $code = $messages[1]->getText();
            $policy
                ->setName($name)
                ->setCode($code)
                ->setDate($date);
            $this->entityManager->persist($policy);

            return $policy;
        } catch (Exception) {
            throw new Exception("exception.policy.edit.policy");
        }
    }
}