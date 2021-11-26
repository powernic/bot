<?php

namespace Powernic\Bot\Emias\Policy\CallbackHandler;

use Doctrine\ORM\EntityNotFoundException;
use Powernic\Bot\Emias\Policy\Service\PolicyService;
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
            $responseMessage = $this->translator->trans(
                'emias.policy.remove.success',
                ['%police_name%' => $policy->getName()]
            );
        } catch (EntityNotFoundException $e) {
            $responseMessage = $this->translator->trans('exception.policy.remove');
        }
        $this->bot->sendMessage($userId, $responseMessage);
    }
}
