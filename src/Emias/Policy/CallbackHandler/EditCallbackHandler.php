<?php

namespace Powernic\Bot\Emias\Policy\CallbackHandler;

use Doctrine\ORM\EntityManager;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Framework\CallbackHandler\CallbackHandler;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;

/**
 * @method MessageCatalogueInterface[] getCatalogues()
 */
class EditCallbackHandler extends CallbackHandler
{

    private EntityManager $entityManager;
    private BotApi $bot;
    private TranslatorInterface $translator;

    public function __construct(BotApi $bot, EntityManager $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->bot = $bot;
        $this->translator = $translator;
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
        $responseMessage = $this->translator->trans("emias.policy.edit.info", [
            "%name%" => $policy->getName(),
            "%code%" => $policy->getCode(),
            "%date%" => $policy->getDate()->format("Y-m-d"),
        ]);
        $this->bot->sendMessage(
            $query->getMessage()->getChat()->getId(),
            $responseMessage
        );
    }
}
