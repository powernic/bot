<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\CallbackHandler;

use Powernic\Bot\Emias\Entity\Doctor;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Emias\Repository\DoctorRepository;
use Powernic\Bot\Emias\Repository\SpecialityRepository;
use Powernic\Bot\Emias\Subscription\Doctor\Service\DoctorSubscriptionService;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use TelegramBot\Api\BotApi;

class OneDoctorAllDay extends CallbackHandler
{
    private SpecialityRepository $specialityRepository;
    private DoctorSubscriptionService $doctorSubscriptionService;
    private BotApi $bot;
    private DoctorRepository $doctorRepository;

    public function __construct(
        BotApi $bot,
        DoctorRepository $doctorRepository,
        SpecialityRepository $specialityRepository,
        DoctorSubscriptionService $doctorSubscriptionService
    ) {
        $this->bot = $bot;
        $this->specialityRepository = $specialityRepository;
        $this->doctorSubscriptionService = $doctorSubscriptionService;
        $this->doctorRepository = $doctorRepository;
    }

    public function handle(): void
    {
        $policyId = (int)$this->getParameter("id");
        $doctorId = (int)$this->getParameter("doctorId");
        /** @var Doctor $doctor */
        $doctor = $this->doctorRepository->find($doctorId);
        $this->doctorSubscriptionService->registerOnOneDoctorAllDaySubscription($policyId, $doctorId);
        $this->bot->sendMessage(
            $this->message->getChat()->getId(),
            sprintf(
                "Вы успешно подписаны на уведомления о ближайшей записи к \"%s %s %s (%s)\"",
                $doctor->getLastName(),
                $doctor->getFirstName(),
                $doctor->getSecondName(),
                $doctor->getSpeciality()->getName()
            ),
        );
    }
}
