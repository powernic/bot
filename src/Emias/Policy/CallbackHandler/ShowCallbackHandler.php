<?php

namespace Powernic\Bot\Emias\Policy\CallbackHandler;

use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Emias\Policy\Repository\PolicyRepository;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use Symfony\Contracts\Translation\TranslatorInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class ShowCallbackHandler extends CallbackHandler
{
    private TranslatorInterface $translator;
    private PolicyRepository $policyRepository;

    public function __construct(
        BotApi $bot,
        TranslatorInterface $translator,
        PolicyRepository $policyRepository
    ) {
        $this->translator = $translator;
        $this->policyRepository = $policyRepository;
        parent::__construct($bot);
    }

    public function handle(): void
    {
        $id = (int)$this->getParameter("id");
        $keyboard = null;
        /** @var ?Policy $policy */
        $policy = $this->policyRepository->find($id);
        if ($policy) {
            $responseMessage = $this->translator->trans("emias.policy.edit.info", [
                "%name%" => $policy->getName(),
                "%code%" => $policy->getCode(),
                "%date%" => $policy->getDate()->format("Y-m-d"),
            ]);
            $actionButtons = $this->getActionButtons($id);
            $keyboard = new InlineKeyboardMarkup($actionButtons);
        } else {
            $responseMessage = $this->translator->trans("exception.policy.not_found");
        }
        $this->bot->sendMessage(
            $this->message->getChat()->getId(),
            $responseMessage,
            null,
            false,
            null,
            $keyboard
        );
    }

    private function getActionButtons(int $policyId): array
    {
        return [
            [
                ['text' => '➖ Удалить', 'callback_data' => 'emiaspolicy:remove:'.$policyId],
                ['text' => '➕ Редактировать', 'callback_data' => 'emiaspolicy:edit'.$policyId],
            ],
        ];
    }
}
