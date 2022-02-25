<?php

namespace Powernic\Bot\Emias\Policy\TextHandler;

use Exception;
use Powernic\Bot\Chat\Service\MessageService;
use Powernic\Bot\Chat\Service\UserService;
use Powernic\Bot\Emias\Policy\Form\PolicyForm;
use Powernic\Bot\Emias\Policy\Service\PolicyService;
use Powernic\Bot\Framework\Handler\Text\TextHandler;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Contracts\Translation\TranslatorInterface;
use TelegramBot\Api\BotApi;

class EditTextHandler extends TextHandler
{
    private PolicyForm $form;
    private TranslatorInterface $translator;
    private BotApi $bot;
    private MessageService $messageService;
    private PolicyService $policyService;
    private UserService $userService;

    public function __construct(
        BotApi $bot,
        TranslatorInterface $translator,
        PolicyForm $form,
        MessageService $messageService,
        PolicyService $policyService,
        UserService $userService,
    ) {
        $this->form = $form;
        $this->translator = $translator;
        $this->bot = $bot;
        $this->messageService = $messageService;
        $this->policyService = $policyService;
        $this->userService = $userService;
    }

    public function handle(): void
    {
        try {
            $responseMessage = $this->translator->trans($this->handleRequest());
            $userId = $this->message->getChat()->getId();
            $this->setRoute($this->userService->getCurrentAction($userId));
            if ($this->form->isLastFieldRequest()) {
                $lastFormField = $this->message->getText();
                $id = (int)$this->getParameter("id");
                $policy = $this->policyService->editPolicy($id, $userId, $lastFormField);
                $responseMessage = $this->translator->trans(
                    "emias.policy.edit.success",
                    ['%police_name%' => $policy->getName()]
                );
            }
            $this->messageService->saveMessage($this->message, $this->getRoute());
        } catch (ValidationFailedException $e) {
            $responseMessage = $e->getViolations()->get(0)->getMessage();
        } catch (Exception $e) {
            $responseMessage = $this->translator->trans($e->getMessage());
        }

        $this->bot->sendMessage(
            $this->message->getChat()->getId(),
            $responseMessage
        );
    }

    private function handleRequest(): string
    {
        return $this->form->setRequest($this->message)
            ->validate()
            ->handleRequest();
    }
}
