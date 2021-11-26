<?php

namespace Powernic\Bot\Chat\Service;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Powernic\Bot\Chat\Entity\Message as EntityMessage;
use Powernic\Bot\Chat\Entity\User;
use Powernic\Bot\Chat\Repository\UserRepository;
use TelegramBot\Api\Types\Message;

final class MessageService
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws Exception
     */
    public function saveMessage(Message $message, string $action): void
    {
        try {
            $user = $this->getUser($message->getChat()->getId());
            $entityMessage = (new EntityMessage())
                ->setId($message->getMessageId())
                ->setUser($user)
                ->setActionCode($action)
                ->setTime((new DateTime())->setTimestamp($message->getDate()))
                ->setText($message->getText());
            $this->entityManager->persist($entityMessage);
            $this->entityManager->flush();
        } catch (Exception) {
            throw new Exception("exception.policy.add.message");
        }
    }

    private function getUser(int $userId): User
    {
        return $this->userRepository->find($userId);
    }
}
