<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\CallbackHandler;

use DateTime;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Emias\Exception\SubscriptionExistsException;
use Powernic\Bot\Emias\Repository\SpecialityRepository;
use Powernic\Bot\Emias\Subscription\Doctor\Service\DoctorSubscriptionService;
use Powernic\Bot\Framework\Chat\Calendar\Handler\DateIntervalHandlerInterface;
use Powernic\Bot\Framework\Chat\Calendar\Selector\SelectorFactory;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use Symfony\Contracts\Translation\TranslatorInterface;
use TelegramBot\Api\BotApi;

class AllDoctorOneDay extends CallbackHandler implements DateIntervalHandlerInterface
{
    private SpecialityRepository $specialityRepository;
    private DoctorSubscriptionService $doctorSubscriptionService;
    private SelectorFactory $selectorFactory;
    private TranslatorInterface $translator;

    public function __construct(
        BotApi $bot,
        SpecialityRepository $specialityRepository,
        DoctorSubscriptionService $doctorSubscriptionService,
        SelectorFactory $selectorFactory,
        TranslatorInterface $translator
    ) {
        $this->specialityRepository = $specialityRepository;
        $this->doctorSubscriptionService = $doctorSubscriptionService;
        $this->selectorFactory = $selectorFactory;
        parent::__construct($bot);
        $this->translator = $translator;
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
        try {
            $this->doctorSubscriptionService->registerOnAllDoctorOneDaySubscription(
                $policyId,
                $specialityId,
                $startTime,
                $endTime
            );
            $message = $this->translator->trans("emias.subscription.alldoc.oneday", [
                "%from_date%" => $startTime->format('d.m.Y H:i'),
                "%to_date%" => $endTime->format('d.m.Y H:i'),
                "%speciality%" => $speciality->getName()
            ]);
        }catch(SubscriptionExistsException $e){
            $message = $this->translator->trans($e->getMessage());
        }
        $this->sendResponse($message);
    }
}
