<?php

namespace Powernic\Bot\TextHandler;

use Doctrine\ORM\EntityManager;
use Powernic\Bot\CallbackHandler\CallbackHandlerLoader;
use Powernic\Bot\Entity\Chat\User;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;
use TelegramBot\Api\Types\Update;

class CallbackTextHandler implements TextHandlerInterface
{
    private EntityManager $entityManager;
    private BotApi $bot;
    private CallbackHandlerLoader $callbackHandlerLoader;

    public function __construct(
        EntityManager $entityManager,
        BotApi $bot,
        CallbackHandlerLoader $callbackHandlerLoader
    ) {
        $this->entityManager = $entityManager;
        $this->bot = $bot;
        $this->callbackHandlerLoader = $callbackHandlerLoader;
    }

    /**
     * @throws Exception|InvalidArgumentException
     */
    public function handle(Update $update): void
    {
        $message = $update->getMessage();
        $userRepository = $this->entityManager->getRepository(User::class);
        $id = $message->getChat()->getId();
        /** @var ?User $user */
        $user = $userRepository->find($id);
        if ($user) {
            $handlerCode = $user->getActionCode();
            $handler = $this->callbackHandlerLoader->get($handlerCode);
            $handler->setUpdate($update)->textHandle();
        } else {
            $this->bot->sendMessage($id, "Привет!");
        }
    }
}