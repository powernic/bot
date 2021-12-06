<?php

namespace Powernic\Bot\Emias\Subscription\CommandHandler;

use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Emias\Policy\Repository\PolicyRepository;
use Powernic\Bot\Framework\Handler\Command\CommandHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class EmiasSubscriptionCommandHandler extends CommandHandler
{
    /**
     * @var \Powernic\Bot\Emias\Policy\Repository\PolicyRepository
     */
    private PolicyRepository $policyRepository;
    /**
     * @var \TelegramBot\Api\BotApi
     */
    private BotApi $bot;

    public function __construct(PolicyRepository $policyRepository, BotApi $bot)
    {
        $this->policyRepository = $policyRepository;
        $this->bot = $bot;
    }

    public function handle(): void
    {
        $userId = $this->message->getChat()->getId();
        $policies = $this->policyRepository->findByUserId($userId);
        $policyButtons = $this->getPolicyButtons($policies);
        $keyboard = new InlineKeyboardMarkup($policyButtons);
        $this->bot->sendMessage(
            $this->message->getChat()->getId(),
            empty($policies) ? "Список Полисов пуст" : "Выберите по какому полису записаться:",
            null,
            false,
            null,
            $keyboard
        );
    }

    /**
     * @param Policy[] $policies
     * @return array
     */
    private function getPolicyButtons(array $policies): array
    {
        $buttons = [];
        foreach ($policies as $policy) {
            $buttons [] = [['text' => $policy->getName(), 'callback_data' => 'emiassub:'.$policy->getId()]];
        }

        return $buttons;
    }
}