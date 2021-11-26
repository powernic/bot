<?php

namespace Powernic\Bot\Emias\Policy\CallbackHandler;

use Doctrine\ORM\EntityManager;
use Powernic\Bot\Chat\Service\UserService;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Emias\Policy\Form\PolicyForm;
use Powernic\Bot\Emias\Policy\Repository\PolicyRepository;
use Powernic\Bot\Framework\Exception\UnexpectedRequestException;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use Symfony\Contracts\Translation\TranslatorInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;


class EditCallbackHandler extends CallbackHandler
{

    private BotApi $bot;
    private UserService $userService;
    private TranslatorInterface $translator;
    private PolicyForm $form;

    public function __construct(
        BotApi $bot,
        UserService $userService,
        TranslatorInterface $translator,
        PolicyForm $form
    ) {
        $this->bot = $bot;
        $this->userService = $userService;
        $this->translator = $translator;
        $this->form = $form;
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function handle(): void
    {
        $userId = $this->message->getChat()->getId();
        $this->userService->setUserAction($userId, $this->message, $this->getRoute());
        try {
            $responseMessage = $this->handleRequest();
        } catch (UnexpectedRequestException) {
            $responseMessage = $this->translator->trans("exception.unexpected.request");
        }
        $this->bot->sendMessage($userId, $this->translator->trans($responseMessage));
    }

    /**
     * @throws UnexpectedRequestException
     */
    private function handleRequest(): string
    {
        return $this->form->setRequest($this->message, true)
            ->validate()
            ->handleRequest();
    }
}
