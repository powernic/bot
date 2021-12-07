<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\CallbackHandler;

use Graze\GuzzleHttp\JsonRpc\Exception\RequestException;
use Powernic\Bot\Emias\Service\DoctorService;
use Powernic\Bot\Emias\Service\EmiasService;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final class ConcreteDoctor extends CallbackHandler
{
    /**
     * @var \TelegramBot\Api\BotApi
     */
    private BotApi $bot;
    private EmiasService $emiasService;
    private DoctorService $doctorService;

    public function __construct(BotApi $bot, EmiasService $emiasService, DoctorService $doctorService)
    {
        $this->bot = $bot;
        $this->emiasService = $emiasService;
        $this->doctorService = $doctorService;
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

    /**
     * @throws \JsonMapper_Exception
     */
    private function getDoctorButtons(): array
    {
        $buttons = [];
        $userId = $this->message->getChat()->getId();
        $policyId = (int)$this->getParameter("id");
        $specialityId = (int)$this->getParameter("speciality");
        $doctorInfoCollection = $this->emiasService->getDoctorsInfo(
            $userId,
            $policyId,
            $specialityId
        );
        $this->doctorService->saveDoctors($doctorInfoCollection, $specialityId);
        foreach ($doctorInfoCollection as $doctorInfo) {
            $doctor = $doctorInfo->getMainDoctor();
            $buttons [] = [
                [
                    'text' => $doctor->getLastName() . " " .
                        $doctor->getFirstName() . " " .
                        $doctor->getSecondName(),
                    'callback_data' => 'emiassub:' . $policyId . ':doctor:' .
                        $specialityId . ':onedoc:' . $doctor->getEmployeeId(),
                ],
            ];
        }

        return $buttons;
    }

}
