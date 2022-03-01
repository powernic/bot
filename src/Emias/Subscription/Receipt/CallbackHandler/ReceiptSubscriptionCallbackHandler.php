<?php

namespace Powernic\Bot\Emias\Subscription\Receipt\CallbackHandler;

use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class ReceiptSubscriptionCallbackHandler extends CallbackHandler
{

    public function handle(): void
    {
        $policyId = (int)$this->getParameter("id");
        $typeButtons = $this->getDateTypeButtons($policyId);
        $keyboard = new InlineKeyboardMarkup($typeButtons);
        $this->bot->sendMessage(
            $this->message->getChat()->getId(),
            "День записи:",
            null,
            false,
            null,
            $keyboard
        );
    }

    private function getDateTypeButtons(int $policyId)
    {
        $buttons = [];
        $types = ["all" => "Любой день", 'one' => 'Конкретный день'];
        foreach ($types as $type => $label) {
            $buttons [] = [['text' => $label, 'callback_data' => 'emiassub:'.$policyId.':receipt:'.$type]];
        }

        return $buttons;
    }
}
