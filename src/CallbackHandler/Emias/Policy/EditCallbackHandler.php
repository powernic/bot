<?php

namespace Powernic\Bot\CallbackHandler\Emias\Policy;

use Doctrine\ORM\EntityManager;
use Powernic\Bot\CallbackHandler\AbstractCallbackHandler;
use Powernic\Bot\Entity\Emias\Policy;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;

class EditCallbackHandler extends AbstractCallbackHandler
{

    private EntityManager $entityManager;
    private BotApi $bot;

    public function __construct(BotApi $bot, EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->bot = $bot;
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function handle(): void
    {
        $id = (int)$this->getParameter("id");
        $query = $this->getQuery();
        $policyRepository = $this->entityManager->getRepository(Policy::class);
        /** @var Policy $policy */
        $policy = $policyRepository->find($id);
        $text = <<<TAG
Ваш полис: {$policy->getName()}
Номер: {$policy->getCode()}
Дата рождения: {$policy->getDate()->format("Y-m-d")}
Введите новое название полиса:
TAG;
        $this->bot->sendMessage(
            $query->getMessage()->getChat()->getId(),
            $text
        );
    }
}
