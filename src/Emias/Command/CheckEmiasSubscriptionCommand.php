<?php

namespace Powernic\Bot\Emias\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Powernic\Bot\Emias\Entity\ScheduleInfo;
use Powernic\Bot\Emias\Service\EmiasService;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\SpecialitySubscription;
use Powernic\Bot\Emias\Subscription\Doctor\Repository\DoctorSubscriptionRepository;
use Powernic\Bot\Emias\Subscription\Doctor\Service\DoctorSubscriptionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TelegramBot\Api\BotApi;

class CheckEmiasSubscriptionCommand extends Command
{
    protected static $defaultName = 'app:emias:subscription:check';
    private EmiasService $emiasService;
    private DoctorSubscriptionRepository $doctorSubscriptionRepository;
    private EntityManager $entityManager;
    private BotApi $bot;
    private TranslatorInterface $translator;

    public function __construct(
        EmiasService $emiasService,
        DoctorSubscriptionRepository $doctorSubscriptionRepository,
        EntityManager $entityManager,
        BotApi $bot,
        TranslatorInterface $translator
    ) {
        $this->emiasService = $emiasService;
        $this->doctorSubscriptionRepository = $doctorSubscriptionRepository;
        $this->entityManager = $entityManager;
        parent::__construct();
        $this->bot = $bot;
        $this->translator = $translator;
    }

    protected function configure()
    {
        $this
            ->setDescription('Check all Emias subscriptions');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            /** @var SpecialitySubscription[] $specialitySubscriptions */
            $specialitySubscriptions = $this->doctorSubscriptionRepository->findAll();
            foreach ($specialitySubscriptions as $specialitySubscription) {
                if ($specialitySubscription->hasTargetTimeInterval()) {
                    $nearestSchedule = $this->emiasService->getNearestScheduleInfoInConcreteDay(
                        $specialitySubscription->getPolicy(),
                        $specialitySubscription->getSpeciality(),
                        $specialitySubscription->getStartTimeInterval()
                    );
                } else {
                    $nearestSchedule = $this->emiasService->getNearestScheduleInfo(
                        $specialitySubscription->getPolicy(),
                        $specialitySubscription->getSpeciality()
                    );
                }
                $isNewNearestSchedule = true;
                if ($specialitySubscription->getScheduleInfo()) {
                    $isNewNearestSchedule = $nearestSchedule->getStartTime() <
                        $specialitySubscription->getScheduleInfo()->getStartTime();
                }
                if ($isNewNearestSchedule) {
                    $this->onNewNearestSchedule($specialitySubscription, $nearestSchedule);
                }
            }
            $this->entityManager->flush();
        } catch (\JsonMapper_Exception $e) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * @param SpecialitySubscription $specialitySubscription
     * @param ScheduleInfo|null $nearestSchedule
     * @return void
     * @throws ORMException
     */
    protected function onNewNearestSchedule(
        SpecialitySubscription $specialitySubscription,
        ?ScheduleInfo $nearestSchedule
    ): void {
        $specialitySubscription->setScheduleInfo($nearestSchedule);
        $this->entityManager->persist($specialitySubscription);

        $this->bot->sendMessage(
            $specialitySubscription->getPolicy()->getUser()->getId(),
            $this->translator->trans("emias.subscription.new_schedule", [
                "%speciality%" => $specialitySubscription->getSpeciality()->getName(),
                '%date_time%' => $nearestSchedule->getStartTime()->format("d.m.Y H:i"),
                "%address%" => $nearestSchedule->getAddress()
            ])

        );
    }
}
