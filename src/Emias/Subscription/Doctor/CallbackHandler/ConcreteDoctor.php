<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\CallbackHandler;

use Graze\GuzzleHttp\JsonRpc\Exception\RequestException;
use Powernic\Bot\Emias\API\Entity\Doctor;
use Powernic\Bot\Emias\API\Repository\DoctorRepository;
use Powernic\Bot\Emias\Service\DoctorService;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final class ConcreteDoctor extends CallbackHandler
{

    private BotApi $bot;
    private DoctorService $doctorService;
    private DoctorRepository $doctorRepository;

    public function __construct(BotApi $bot, DoctorService $doctorService, DoctorRepository $doctorRepository)
    {

        $this->bot = $bot;
        $this->doctorService = $doctorService;
        $this->doctorRepository = $doctorRepository;
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
        $policyId = (int)$this->getParameter("id");
        $specialityId = (int)$this->getParameter("speciality");
        $doctorCollection = $this->doctorRepository->findBySpeciality(
            $policyId,
            $specialityId
        );
        $this->doctorService->saveDoctors($doctorCollection, $specialityId);
        return $doctorCollection->sortByAvailable()->map(fn(Doctor $doctor) => [
            [
                'text' => $doctor->getAvailableMark() . ' ' . $doctor->getFullName(),
                'callback_data' => 'emiassub:' . $policyId . ':doctor:' .
                    $specialityId . ':onedoc:' . $doctor->getEmployeeId(),
            ],
        ])->getValues();
    }

}
