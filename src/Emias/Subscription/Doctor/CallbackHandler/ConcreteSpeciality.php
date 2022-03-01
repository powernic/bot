<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\CallbackHandler;

use Doctrine\ORM\EntityManagerInterface;
use Graze\GuzzleHttp\JsonRpc\Exception\RequestException;
use Powernic\Bot\Emias\API\Entity\SpecialityCollection;
use Powernic\Bot\Emias\API\Repository\SpecialityRepository as ApiSpecialityRepository;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Emias\Repository\SpecialityRepository;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class ConcreteSpeciality extends CallbackHandler
{
    private ApiSpecialityRepository $apiSpecialityRepository;
    private EntityManagerInterface $entityManager;
    private SpecialityRepository $specialityRepository;

    public function __construct(
        BotApi $bot,
        ApiSpecialityRepository $apiSpecialityRepository,
        SpecialityRepository $specialityRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->apiSpecialityRepository = $apiSpecialityRepository;
        parent::__construct($bot);
        $this->entityManager = $entityManager;
        $this->specialityRepository = $specialityRepository;
    }

    public function handle(): void
    {
        $keyboard = null;
        $responseMessage = "Специальность:";
        try {
            $typeButtons = $this->getSpecialtyButtons();
            $keyboard = new InlineKeyboardMarkup($typeButtons);
        } catch (RequestException $e) {
            $responseMessage = $e->getResponse()->getRpcErrorMessage();
        }

        $chatId = $this->message->getChat()->getId();
        $messageId = $this->message->getMessageId();
        $this->bot->editMessageText($chatId, $messageId, $responseMessage);
        $this->bot->editMessageReplyMarkup($chatId, $messageId, $keyboard);
    }

    /**
     * @return array
     */
    private function getSpecialtyButtons(): array
    {
        $buttons = [];
        $userId = $this->message->getChat()->getId();
        $policyId = (int)$this->getParameter("id");
        $specialities = $this->apiSpecialityRepository->findByUserPolicy($userId, $policyId);
        $this->updateSpecialities($specialities);
        foreach ($specialities as $speciality) {
            $buttons [] = [
                [
                    'text' => $speciality->getName(),
                    'callback_data' => 'emiassub:' . $policyId . ':doctor:' . $speciality->getCode(),
                ],
            ];
        }

        return $buttons;
    }

    private function updateSpecialities(SpecialityCollection $specialityCollection): void
    {
        foreach ($specialityCollection as $specialityDto) {
            $speciality = $this->specialityRepository->find($specialityDto->getCode());
            if (!($speciality)) {
                $speciality = (new Speciality())
                    ->setName($specialityDto->getName())
                    ->setCode($specialityDto->getCode());
            }
            $this->entityManager->persist($speciality);
        }
        $this->entityManager->flush();
    }
}
