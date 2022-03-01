<?php

namespace Powernic\Bot\Emias\Policy\CommandHandler;

use Doctrine\ORM\EntityManager;
use Powernic\Bot\Framework\Handler\Command\CommandHandler;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final class EmiasPolicyCommandHandler extends CommandHandler
{
    private BotApi $bot;
    private EntityManager $entityManager;

    public function __construct(BotApi $bot, EntityManager $entityManager)
    {
        $this->bot = $bot;
        $this->entityManager = $entityManager;
    }

    public function handle(): void
    {
        $policies = $this->getPolicies();
        $policyButtons = $this->getPolicyButtons($policies);
        $actionButtons = $this->getActionButtons();
        $keyboard = new InlineKeyboardMarkup(array_merge($policyButtons, $actionButtons));
        $this->bot->sendMessage(
            $this->message->getChat()->getId(),
            empty($policies) ? "Список Полисов пуст" : "Список полисов:",
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
            $buttons [] = [['text' => $policy->getName(), 'callback_data' => 'emiaspolicy:show:' . $policy->getId()]];
        }

        return $buttons;
    }

    private function getActionButtons(): array
    {
        return [
            [
                ['text' => '➕ Добавить', 'callback_data' => 'emiaspolicy:add'],
            ],
        ];
    }

    /**
     * @return Policy[]
     */
    private function getPolicies(): array
    {
        $policyRepository = $this->entityManager->getRepository(Policy::class);

        return $policyRepository->findAll();
    }
}
