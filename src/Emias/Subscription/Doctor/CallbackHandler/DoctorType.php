<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\CallbackHandler;

use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class DoctorType extends CallbackHandler
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
        $speciality = (int)$this->getParameter("speciality");
        $typeButtons = $this->getDoctorTypeButtons($policyId, $speciality);
        $keyboard = new InlineKeyboardMarkup($typeButtons);
        $this->bot->sendMessage(
            $this->message->getChat()->getId(),
            "Запись к врачу:",
            null,
            false,
            null,
            $keyboard
        );
    }

    private function getDoctorTypeButtons(int $policyId, string $speciality): array
    {
        $buttons = [];
        $types = ['alldoc' => "К любому врачу", 'onedoc' => 'К конкретному врачу'];
        foreach ($types as $type => $label) {
            $buttons [] = [
                [
                    'text' => $label,
                    'callback_data' => 'emiassub:'.$policyId.':doctor:'.$speciality.':'.$type,
                ],
            ];
        }

        return $buttons;
    }
}
