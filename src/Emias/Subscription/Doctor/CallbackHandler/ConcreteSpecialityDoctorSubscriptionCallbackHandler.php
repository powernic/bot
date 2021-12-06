<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\CallbackHandler;

use Graze\GuzzleHttp\JsonRpc\Exception\RequestException;
use JsonMapper_Exception;
use Powernic\Bot\Emias\Service\EmiasService;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class ConcreteSpecialityDoctorSubscriptionCallbackHandler extends CallbackHandler
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
        $responseMessage = "Специальность:";
        try {
            $typeButtons = $this->getSpecialtyButtons();
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

    /**
     * @throws RequestException
     * @throws JsonMapper_Exception
     */
    private function getSpecialtyButtons(): array
    {
        $buttons = [];
        $userId = $this->message->getChat()->getId();
        $policyId = (int)$this->getParameter("id");
        $specialities = $this->emiasService->getSpecialitiesInfo($userId, $policyId);
        foreach ($specialities as $speciality) {
            $buttons [] = [
                [
                    'text' => $speciality->getName(),
                    'callback_data' => 'emiassub:'.$policyId.':doctor:'.$speciality->getCode(),
                ],
            ];
        }

        return $buttons;
    }
}
