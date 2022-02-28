<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\CallbackHandler;

use DateTime;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Emias\Repository\SpecialityRepository;
use Powernic\Bot\Emias\Subscription\Doctor\Service\DoctorSubscriptionService;
use Powernic\Bot\Framework\Chat\Calendar\Handler\DateIntervalHandlerInterface;
use Powernic\Bot\Framework\Chat\Calendar\Selector\SelectorFactory;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use TelegramBot\Api\BotApi;

class AllDoctorOneDay extends CallbackHandler implements DateIntervalHandlerInterface
{
    private SpecialityRepository $specialityRepository;
    private DoctorSubscriptionService $doctorSubscriptionService;
    private SelectorFactory $selectorFactory;

    public function __construct(
        BotApi $bot,
        SpecialityRepository $specialityRepository,
        DoctorSubscriptionService $doctorSubscriptionService,
        SelectorFactory $selectorFactory
    ) {
        $this->specialityRepository = $specialityRepository;
        $this->doctorSubscriptionService = $doctorSubscriptionService;
        $this->selectorFactory = $selectorFactory;
        parent::__construct($bot);
    }

    public function handle(): void
    {
        $selector = $this->selectorFactory->create('emiassub');
        $this->sendResponse("Выберите год", $selector->getButtons(), true);
    }

    public function handleDateInterval(DateTime $startTime, DateTime $endTime): void
    {
        $policyId = (int)$this->getParameter("id");
        $specialityId = (int)$this->getParameter("speciality");
        /** @var Speciality $speciality */
        $speciality = $this->specialityRepository->find($specialityId);
        $message =  "Вы успешно подписали на уведомления о ближайшей записи в период: " . $startTime->format(
                'd.m.Y H:i'
            ) . " - " . $endTime->format(
                'd.m.Y H:i к ' . $speciality->getName()
            );
        $this->sendResponse($message);
    }
}
