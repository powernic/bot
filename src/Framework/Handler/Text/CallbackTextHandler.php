<?php

namespace Powernic\Bot\Framework\Handler\Text;

use Doctrine\ORM\EntityManager;
use Powernic\Bot\Framework\Handler\AvailableMessageInterface;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandlerLoader;
use Powernic\Bot\Chat\Entity\User;
use Powernic\Bot\Framework\Handler\Handler;
use Powernic\Bot\Framework\Handler\HandlerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;

class CallbackTextHandler extends Handler implements AvailableMessageInterface
{
    private EntityManager $entityManager;
    private BotApi $bot;
    private CallbackHandlerLoader $callbackHandlerLoader;
    private Update $update;
    protected Message $message;

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
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(): void
    {
        $message = $this->update->getMessage();
        $userRepository = $this->entityManager->getRepository(User::class);
        $id = $message->getChat()->getId();
        /** @var ?User $user */
        $user = $userRepository->find($id);
        if ($user) {
            $handlerCode = $user->getActionCode();
            $handler = $this->callbackHandlerLoader->get($handlerCode);
          //  $handler->setUpdate($this->update)->textHandle();
        } else {
            $this->bot->sendMessage($id, "Привет!");
        }
    }

    /**
     * @param Update $update
     * @return CallbackTextHandler
     */
    public function setUpdate(Update $update): CallbackTextHandler
    {
        $this->update = $update;

        return $this;
    }

    /**
     * @param Message $message
     * @return self
     */
    public function setMessage(Message $message): self
    {
        $this->message = $message;

        return $this;
    }
}
