<?php

namespace Powernic\Bot\Emias\Policy\CommandHandler;

use Doctrine\ORM\EntityManager;
use Powernic\Bot\Framework\CommandHandler\CommandHandler;
use Powernic\Bot\Entity\Emias\Policy;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\Message;

final class EmiasPolicyCommandHandler extends CommandHandler
{
    private BotApi $bot;
    private EntityManager $entityManager;

    public function __construct(BotApi $bot, EntityManager $entityManager)
    {
        $this->bot = $bot;
        $this->entityManager = $entityManager;
    }

    public function handle(Message $message): void
    {
        $policyButtons = $this->getPolicyButtons();
        $actionButtons = $this->getActionButtons();
        $keyboard = new InlineKeyboardMarkup(array_merge($policyButtons, $actionButtons));

        $this->bot->sendMessage(
            $message->getChat()->getId(),
            "Список Полисов пуст",
            null,
            false,
            null,
            $keyboard
        );
    }

    private function getPolicyButtons(): array
    {
        $policyRepository = $this->entityManager->getRepository(Policy::class);
        /** @var Policy[] $policies */
        $policies = $policyRepository->findAll();
        $buttons = [];
        foreach ($policies as $policy) {
            $buttons [] = [['text' => $policy->getName(), 'callback_data' => 'emiaspolicy:edit:' . $policy->getId()]];
        }

        return $buttons;
    }

    private function getActionButtons(): array
    {
        return [
            [
                ['text' => '➕ Добавить', 'callback_data' => 'emiaspolicy:add'],
                ['text' => '➖ Удалить', 'callback_data' => 'emiaspolicy:del'],
            ],
        ];
    }
}
