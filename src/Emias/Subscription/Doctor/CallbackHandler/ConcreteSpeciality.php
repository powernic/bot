<?php

namespace Powernic\Bot\Emias\Subscription\Doctor\CallbackHandler;

use Graze\GuzzleHttp\JsonRpc\Exception\RequestException;
use Powernic\Bot\Emias\API\Repository\SpecialityRepository;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class ConcreteSpeciality extends CallbackHandler
{
    private SpecialityRepository $specialityRepository;

    public function __construct(BotApi $bot, SpecialityRepository $specialityRepository)
    {
        $this->specialityRepository = $specialityRepository;
        parent::__construct($bot);
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
        $specialities = $this->specialityRepository->findByUserPolicy($userId, $policyId);
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
}
