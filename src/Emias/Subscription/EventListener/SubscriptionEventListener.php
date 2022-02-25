<?php

namespace Powernic\Bot\Emias\Subscription\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Powernic\Bot\Emias\Entity\Schedule;
use Powernic\Bot\Emias\Subscription\Doctor\Entity\Subscription;
use Symfony\Contracts\Translation\TranslatorInterface;
use TelegramBot\Api\BotApi;

class SubscriptionEventListener
{
    private EntityManagerInterface $entityManager;
    private BotApi $bot;
    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $entityManager, BotApi $bot, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->bot = $bot;
        $this->translator = $translator;
    }

    /**
     * @param Subscription $subscription
     * @param ?Schedule $nearestSchedule
     * @return void
     */
    public function onNewestNearestSchedule(
        Subscription $subscription,
        ?Schedule $nearestSchedule
    ): void {
        $subscription->setSchedule($nearestSchedule);
        $this->entityManager->persist($subscription);

        $this->bot->sendMessage(
            $subscription->getPolicy()->getUser()->getId(),
            $this->translator->trans("emias.subscription.new_schedule", [
                "%speciality%" => $subscription->getSpeciality()->getName(),
                '%date_time%' => $nearestSchedule->getStartTime()->format("d.m.Y H:i"),
                "%address%" => $nearestSchedule->getAddress()
            ])
        );
    }

    public function onNotAvailableSchedule(Subscription $subscription)
    {
        $subscription->setSchedule(null);
        $this->entityManager->persist($subscription);

       /* $this->bot->sendMessage(
            $subscription->getPolicy()->getUser()->getId(),
            $this->translator->trans("emias.subscription.not_available_schedule", [
                "%speciality%" => $subscription->getSpeciality()->getName()
            ])
        );*/
    }
}
