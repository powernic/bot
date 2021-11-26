<?php

namespace Powernic\Bot\Emias\Policy\CallbackHandler;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Emias\Policy\Service\PolicyService;
use Powernic\Bot\Framework\Exception\UnexpectedRequestException;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use Symfony\Contracts\Translation\TranslatorInterface;
use TelegramBot\Api\BotApi;

class RemoveCallbackHandler extends CallbackHandler
{
    private PolicyService $policyService;
    private TranslatorInterface $translator;
    private BotApi $bot;

    public function __construct(PolicyService $policyService, TranslatorInterface $translator, BotApi $bot)
    {
        $this->policyService = $policyService;
        $this->translator = $translator;
        $this->bot = $bot;
    }


    public function handle(): void
    {
        $userId = $this->message->getChat()->getId();
        $policyId = (int)$this->getParameter("id");
        try {
            $policy = $this->policyService->removePolicy($policyId, $userId);
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }
        $this->bot->sendMessage(
            $userId,
            $this->translator->trans(
                'emias.policy.remove.success',
                ['%police_name%' => $policy->getName()]
            )
        );
    }
}
