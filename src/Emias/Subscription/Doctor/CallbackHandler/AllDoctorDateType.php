<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\CallbackHandler;

use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class AllDoctorDateType extends CallbackHandler
{

    public function handle(): void
    {
        $policyId = (int)$this->getParameter("id");
        $speciality = (int)$this->getParameter("speciality");
        $typeButtons = $this->getDateTypeButtons($policyId, $speciality);
        $keyboard = new InlineKeyboardMarkup($typeButtons);
        $chatId = $this->message->getChat()->getId();
        $messageId = $this->message->getMessageId();
        $this->bot->editMessageText($chatId, $messageId, "День записи:");
        $this->bot->editMessageReplyMarkup($chatId, $messageId, $keyboard);
    }

    private function getDateTypeButtons(int $policyId, int $speciality)
    {
        $buttons = [];
        $types = ["allday" => "Любой день", 'oneday' => 'Конкретный день'];
        foreach ($types as $type => $label) {
            $buttons [] = [
                [
                    'text' => $label,
                    'callback_data' => 'emiassub:' . $policyId . ':doctor:' . $speciality . ':alldoc:' . $type,
                ],
            ];
        }

        return $buttons;
    }
}
