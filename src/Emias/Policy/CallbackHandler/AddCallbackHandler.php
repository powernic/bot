<?php

namespace Powernic\Bot\Emias\Policy\CallbackHandler;

use Powernic\Bot\Chat\Service\UserService;
use Powernic\Bot\Emias\Policy\Form\PolicyForm;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use Powernic\Bot\Framework\Exception\UnexpectedRequestException;
use Symfony\Contracts\Translation\TranslatorInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception as BotException;

class AddCallbackHandler extends CallbackHandler
{
    private TranslatorInterface $translator;
    private PolicyForm $form;
    private UserService $userService;

    public function __construct(
        BotApi $bot,
        UserService $userService,
        TranslatorInterface $translator,
        PolicyForm $form
    ) {
        $this->translator = $translator;
        $this->form = $form;
        $this->userService = $userService;
        parent::__construct($bot);
    }

    /**
     * @throws BotException
     */
    public function handle(): void
    {
        $userId = $this->message->getChat()->getId();
        $this->userService->setUserAction($userId, $this->message, $this->getName());
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
