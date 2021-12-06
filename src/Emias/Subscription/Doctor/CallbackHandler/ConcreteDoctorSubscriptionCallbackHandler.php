<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\CallbackHandler;

use Graze\GuzzleHttp\JsonRpc\Exception\RequestException;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Emias\Service\EmiasService;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final class ConcreteDoctorSubscriptionCallbackHandler extends CallbackHandler
{
    /**
     * @var \TelegramBot\Api\BotApi
     */
    private BotApi $bot;
    private EmiasService $emiasService;

    public function __construct(BotApi $bot, EmiasService $emiasService)
    {
        $this->bot = $bot;
        $this->emiasService = $emiasService;
    }

    public function handle(): void
    {
        $keyboard = null;
        $responseMessage = "Доктор:";
        try {
            $typeButtons = $this->getDoctorButtons();
            $keyboard = new InlineKeyboardMarkup($typeButtons);
        } catch (RequestException $e) {
            $responseMessage = $e->getResponse()->getRpcErrorMessage();
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

    private function getDoctorButtons(): array
    {
        $buttons = [];
        $userId = $this->message->getChat()->getId();
        $policyId = (int)$this->getParameter("id");
        $specialityId = (int)$this->getParameter("speciality");
        $specialities = $this->emiasService->getDoctorsInfo(
            $userId,
            $policyId,
            (new Speciality())->setCode($specialityId)
        );
        foreach ($specialities as $speciality) {
            $buttons [] = [
                [
                    'text' => $speciality->getName(),
                    'callback_data' => 'emiassub:' . $policyId . ':doctor:' . $speciality->getCode(),
                ],
            ];
        }

        return $buttons;
    }

}
