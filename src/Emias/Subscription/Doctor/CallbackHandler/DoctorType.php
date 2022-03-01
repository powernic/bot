<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\CallbackHandler;

use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class DoctorType extends CallbackHandler
{

    public function handle(): void
    {
        $policyId = (int)$this->getParameter("id");
        $speciality = (int)$this->getParameter("speciality");
        $typeButtons = $this->getDoctorTypeButtons($policyId, $speciality);
        $keyboard = new InlineKeyboardMarkup($typeButtons);
        $chatId = $this->message->getChat()->getId();
        $messageId = $this->message->getMessageId();
        $this->bot->editMessageText($chatId, $messageId, "Запись к врачу:");
        $this->bot->editMessageReplyMarkup($chatId, $messageId, $keyboard);
    }

    private function getDoctorTypeButtons(int $policyId, string $speciality): array
    {
        $buttons = [];
        $types = ['alldoc' => "К любому врачу", 'onedoc' => 'К конкретному врачу'];
        foreach ($types as $type => $label) {
            $buttons [] = [
                [
                    'text' => $label,
                    'callback_data' => 'emiassub:' . $policyId . ':doctor:' . $speciality . ':' . $type,
                ],
            ];
        }

        return $buttons;
    }
}
