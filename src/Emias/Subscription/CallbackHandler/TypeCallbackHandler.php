<?php

namespace Powernic\Bot\Emias\Subscription\CallbackHandler;

use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class TypeCallbackHandler extends CallbackHandler
{
    /**
     * @var \TelegramBot\Api\BotApi
     */
    private BotApi $bot;

    public function __construct(BotApi $bot)
    {
        $this->bot = $bot;
    }

    public function handle(): void
    {
        $policyId = (int)$this->getParameter("id");
        $typeButtons = $this->getTypeButtons($policyId);
        $keyboard = new InlineKeyboardMarkup($typeButtons);
        $this->bot->sendMessage(
            $this->message->getChat()->getId(),
            "Тип записи:",
            null,
            false,
            null,
            $keyboard
        );
    }

    private function getTypeButtons(int $policyId): array
    {
        $buttons = [];
        $types = ["receipt" => "Направление", 'doctor' => 'Врач'];
        foreach ($types as $type => $label) {
            $buttons [] = [['text' => $label, 'callback_data' => 'emiassub:' . $policyId . ':' . $type]];
        }

        return $buttons;
    }
}
