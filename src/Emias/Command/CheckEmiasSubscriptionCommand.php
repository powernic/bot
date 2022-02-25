<?php

namespace Powernic\Bot\Emias\Command;

use Powernic\Bot\Emias\Subscription\Doctor\Entity\DoctorSubscription;
use Powernic\Bot\Emias\Subscription\Doctor\Service\DoctorSubscriptionService;
use Powernic\Bot\Emias\Subscription\Doctor\Service\SpecialitySubscriptionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckEmiasSubscriptionCommand extends Command
{
    protected static $defaultName = 'app:emias:subscription:check';
    private SpecialitySubscriptionService $specialitySubscriptionService;
    private DoctorSubscriptionService $doctorSubscriptionService;

    public function __construct(
        SpecialitySubscriptionService $specialitySubscriptionService,
        DoctorSubscriptionService $doctorSubscriptionService
    ) {
        $this->specialitySubscriptionService = $specialitySubscriptionService;
        $this->doctorSubscriptionService = $doctorSubscriptionService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Check all Emias subscriptions');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->specialitySubscriptionService->processNearestSchedule();
            $this->doctorSubscriptionService->processNearestSchedule();
        } catch (\JsonMapper_Exception $e) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
