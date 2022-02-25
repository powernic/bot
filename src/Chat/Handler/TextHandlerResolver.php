<?php

namespace Powernic\Bot\Chat\Handler;

use Powernic\Bot\Chat\Entity\User;
use Powernic\Bot\Chat\Repository\UserRepository;
use Powernic\Bot\Framework\Handler\Resolver\TextHandlerResolverInterface;
use TelegramBot\Api\Types\Message;

final class TextHandlerResolver implements TextHandlerResolverInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function resolve(Message $message): string
    {
        $id = $message->getChat()->getId();
        /** @var ?User $user */
        $user = $this->userRepository->find($id);
        if ($user) {
            return $user->getAction()->getCode();
        }

        return "";
    }
}
