<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\CallbackHandler;

use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Emias\Exception\SubscriptionExistsException;
use Powernic\Bot\Emias\Repository\SpecialityRepository;
use Powernic\Bot\Emias\Subscription\Doctor\Service\DoctorSubscriptionService;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use Symfony\Contracts\Translation\TranslatorInterface;
use TelegramBot\Api\BotApi;

class AllDoctorAllDay extends CallbackHandler
{
    private SpecialityRepository $specialityRepository;
    private DoctorSubscriptionService $doctorSubscriptionService;
    private TranslatorInterface $translator;

    public function __construct(
        BotApi $bot,
        SpecialityRepository $specialityRepository,
        DoctorSubscriptionService $doctorSubscriptionService,
        TranslatorInterface $translator
    ) {
        $this->specialityRepository = $specialityRepository;
        $this->doctorSubscriptionService = $doctorSubscriptionService;
        parent::__construct($bot);
        $this->translator = $translator;
    }

    public function handle(): void
    {
        $policyId = (int)$this->getParameter("id");
        $specialityId = (int)$this->getParameter("speciality");
        /** @var Speciality $speciality */
        $speciality = $this->specialityRepository->find($specialityId);
        try {
            $this->doctorSubscriptionService->registerOnAllDaySubscription($policyId, $specialityId);
            $this->bot->sendMessage(
                $this->message->getChat()->getId(),
                "Вы успешно подписаны на уведомления о ближайшей записи к '{$speciality->getName()}'",
            );
        } catch (SubscriptionExistsException $e) {
            $this->bot->sendMessage($this->message->getChat()->getId(), $this->translator->trans($e->getMessage()));
        }
    }
}
