<?php

namespace Powernic\Bot\Emias\Policy\TextHandler;

use Exception;
use Powernic\Bot\Chat\Repository\MessageRepository;
use Powernic\Bot\Chat\Service\MessageService;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Emias\Policy\Form\PolicyForm;
use Powernic\Bot\Emias\Policy\Service\PolicyService;
use Powernic\Bot\Framework\Exception\UnexpectedRequestException;
use Powernic\Bot\Framework\Handler\Text\TextHandler;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Contracts\Translation\TranslatorInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;

class AddTextHandler extends TextHandler
{
    private PolicyForm $form;
    private TranslatorInterface $translator;
    private BotApi $bot;
    private MessageService $messageService;
    private PolicyService $policyService;

    public function __construct(
        BotApi $bot,
        TranslatorInterface $translator,
        PolicyForm $form,
        MessageService $messageService,
        PolicyService $policyService
    ) {
        $this->form = $form;
        $this->translator = $translator;
        $this->bot = $bot;
        $this->messageService = $messageService;
        $this->policyService = $policyService;
    }

    public function handle(): void
    { 
        try {
            $responseMessage = $this->translator->trans($this->handleRequest());
            if ($this->form->isLastFieldRequest()) {
                $userId = $this->message->getChat()->getId();
                $lastFormField = $this->message->getText();
                $policy = $this->policyService->addPolicy($userId, $lastFormField);
                $responseMessage = $this->translator->trans(
                    "emias.policy.add.success",
                    ['%police_name%' => $policy->getName()]
                );
            }
            $this->messageService->saveMessage($this->message, $this->action);
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
