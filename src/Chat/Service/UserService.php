<?php

namespace Powernic\Bot\Chat\Service;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Powernic\Bot\Chat\Entity\Action;
use Powernic\Bot\Chat\Entity\User;
use Powernic\Bot\Chat\Entity\UserName;
use Powernic\Bot\Chat\Repository\UserRepository;
use Powernic\Bot\Emias\Policy\CallbackHandler\AddCallbackHandler;
use TelegramBot\Api\Types\CallbackQuery;
use TelegramBot\Api\Types\Chat;
use TelegramBot\Api\Types\Message;

final class UserService
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function getCurrentAction(int $userId): string
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId);

        return $user->getAction()->getCode();
    }

    public function save(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param int $userId
     * @param Message $message
     * @param string $actionName
     */
    public function setUserAction(int $userId, Message $message, string $actionName)
    {
        /** @var ?User $user */
        $user = $this->userRepository->find($userId);
        if (!$user) {
            $user = $this->createUser($message->getChat());
        }
        $messageTime = (new DateTime())->setTimestamp($message->getDate());
        $action = new Action($messageTime, $actionName);
        $user->setAction($action);
        $this->save($user);
    }


    /**
     * @param Chat $chat
     * @return User
     */
    private function createUser(Chat $chat): User
    {
        $userName = new UserName($chat->getFirstName(), $chat->getLastName());
        return (new User())
            ->setId($chat->getId())
            ->setUserName($userName)
            ->setAccountName($chat->getUsername());
    }
}
