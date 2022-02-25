<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\CallbackHandler;

use Powernic\Bot\Emias\Entity\Doctor;
use Powernic\Bot\Emias\Exception\SubscriptionExistsException;
use Powernic\Bot\Emias\Repository\DoctorRepository;
use Powernic\Bot\Emias\Subscription\Doctor\Service\DoctorSubscriptionService;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use Symfony\Contracts\Translation\TranslatorInterface;
use TelegramBot\Api\BotApi;

class OneDoctorAllDay extends CallbackHandler
{
    private DoctorSubscriptionService $doctorSubscriptionService;
    private BotApi $bot;
    private DoctorRepository $doctorRepository;
    private TranslatorInterface $translator;

    public function __construct(
        BotApi $bot,
        TranslatorInterface $translator,
        DoctorRepository $doctorRepository,
        DoctorSubscriptionService $doctorSubscriptionService
    ) {
        $this->bot = $bot;
        $this->doctorSubscriptionService = $doctorSubscriptionService;
        $this->doctorRepository = $doctorRepository;
        $this->translator = $translator;
    }

    public function handle(): void
    {
        $policyId = (int)$this->getParameter("id");
        $doctorId = (int)$this->getParameter("doctorId");
        /** @var Doctor $doctor */
        $doctor = $this->doctorRepository->find($doctorId);
        try {
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
        } catch (SubscriptionExistsException $e) {
            $this->bot->sendMessage($this->message->getChat()->getId(), $this->translator->trans($e->getMessage()));
        }
    }
}
