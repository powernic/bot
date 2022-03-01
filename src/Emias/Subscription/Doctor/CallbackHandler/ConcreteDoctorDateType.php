<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\CallbackHandler;

use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class ConcreteDoctorDateType extends CallbackHandler
{
    public function handle(): void
    {
        $policyId = (int)$this->getParameter("id");
        $speciality = (int)$this->getParameter("speciality");
        $doctorId = (int)$this->getParameter("doctorId");
        $typeButtons = $this->getDateTypeButtons($policyId, $speciality, $doctorId);
        $keyboard = new InlineKeyboardMarkup($typeButtons);
        $this->sendResponse("День записи:", $typeButtons);
    }

    private function getDateTypeButtons(int $policyId, int $speciality, int $doctorId): array
    {
        $buttons = [];
        $types = ["allday" => "Любой день", 'oneday' => 'Конкретный день'];
        foreach ($types as $type => $label) {
            $buttons [] = [
                [
                    'text' => $label,
                    'callback_data' => sprintf(
                        "emiassub:%d:doctor:%d:onedoc:%d:%s",
                        $policyId,
                        $speciality,
                        $doctorId,
                        $type
                    ),
                ],
            ];
        }

        return $buttons;
    }
}
