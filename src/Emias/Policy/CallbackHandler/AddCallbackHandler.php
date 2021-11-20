<?php

namespace Powernic\Bot\Emias\Policy\CallbackHandler;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Framework\CallbackHandler\CallbackHandler;
use Powernic\Bot\Chat\Entity\Message;
use Powernic\Bot\Chat\Entity\User;
use Powernic\Bot\Exception\UnexpectedRequestException;
use Powernic\Bot\Chat\Repository\MessageRepository;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception as BotException;
use TelegramBot\Api\InvalidArgumentException;
use TelegramBot\Api\Types\CallbackQuery;

class AddCallbackHandler extends CallbackHandler
{
    private BotApi $bot;
    private EntityManager $entityManager;
    private string $messagePrefix = "emias.policy.add.";
    private array $policyFields = ["name", "code", "date"];
    private ?int $countFilledPolicyFields = null;
    private ValidatorInterface $validator;
    private TranslatorInterface $translator;

    public function __construct(
        BotApi $bot,
        EntityManager $entityManager,
        ValidatorInterface $validator,
        TranslatorInterface $translator,
    ) {
        $this->bot = $bot;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->translator = $translator;
    }

    /**
     * @throws BotException
     * @throws InvalidArgumentException
     * @throws OptimisticLockException
     */
    public function handle(): void
    {
        $query = $this->getQuery();
        $chat = $query->getMessage()->getChat();
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var ?User $user */
        $user = $userRepository->find($chat->getId());
        if (!$user) {
            $user = $this->createUser($query);
        }
        $this->setUserAction($user, $query);
        $firstFieldName = $this->policyFields[0];
        $responseMessage = $this->messagePrefix . $firstFieldName;
        $this->bot->sendMessage(
            $chat->getId(),
            $this->translator->trans($responseMessage)
        );
    }

    /**
     * @throws BotException
     * @throws InvalidArgumentException
     */
    public function textHandle(): void
    {
        parent::textHandle();
        try {
            $this->checkRequest();
            $responseMessage = $this->processField();
            if ($this->isLastFieldRequest()) {
                $policy = $this->addPolicy($this->getMessageText());
                $responseMessage = $this->translator->trans(
                    $this->messagePrefix . "success",
                    ['%police_name%' => $policy->getName()]
                );
            }
            $this->saveMessage();
            $this->entityManager->flush();
        } catch (UnexpectedRequestException) {
            $responseMessage = $this->translator->trans("exception.unexpected.request");
        } catch (ValidationFailedException $e) {
            $responseMessage = $e->getViolations()->get(0)->getMessage();
        } catch (Exception $e) {
            $responseMessage = $this->translator->trans($e->getMessage());
        }

        $this->bot->sendMessage(
            $this->getUpdate()->getMessage()->getChat()->getId(),
            $responseMessage
        );
    }

    /**
     * @return string
     * @throws ValidationFailedException
     */
    private function processField(): string
    {
        $filledPolicyFields = $this->getCountFilledPolicyFields();
        $fieldName = $this->policyFields[$filledPolicyFields];
        $value = $this->getMessageText();
        $errors = $this->validator->validatePropertyValue(Policy::class, $fieldName, $value);
        $hasError = count($errors) > 0;
        if ($hasError) {
            throw new ValidationFailedException($value, $errors);
        }

        return $this->getMessageField();
    }


    /**
     * @param CallbackQuery $query
     * @return User
     */
    private function createUser(CallbackQuery $query): User
    {
        $chat = $query->getMessage()->getChat();

        return (new User())
            ->setId($chat->getId())
            ->setFirstName($chat->getFirstName())
            ->setLastName($chat->getLastName())
            ->setUserName($chat->getUsername());
    }

    /**
     * @throws Exception|OptimisticLockException
     */
    private function setUserAction(User $user, CallbackQuery $query)
    {
        $messageTime = (new DateTime())->setTimestamp($query->getMessage()->getDate());
        $user->setActionTime($messageTime);
        $user->setActionCode($this->getName());
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param string $date
     * @return Policy
     * @throws Exception
     */
    private function addPolicy(string $date): Policy
    {
        try {
            $user = $this->getUser();
            /** @var MessageRepository $messageRepository */
            $messageRepository = $this->entityManager->getRepository(Message::class);
            $messages = $messageRepository->getAllByLastAction($user);
            $name = $messages[0]->getText();
            $code = $messages[1]->getText();
            $policy = (new Policy())
                ->setUser($user)
                ->setName($name)
                ->setCode($code)
                ->setDate($date);
            $this->entityManager->persist($policy);

            return $policy;
        } catch (Exception) {
            throw new Exception("exception.policy.add.policy");
        }
    }

    /**
     * @throws Exception
     */
    private function saveMessage(): void
    {
        try {
            $user = $this->getUser();
            $telegramMessage = $this->getUpdate()->getMessage();
            $message = (new Message())
                ->setId($telegramMessage->getMessageId())
                ->setUser($user)
                ->setActionCode($this->getName())
                ->setTime((new DateTime())->setTimestamp($telegramMessage->getDate()))
                ->setText($this->getMessageText());
            $this->entityManager->persist($message);
        } catch (Exception) {
            throw new Exception("exception.policy.add.message");
        }
    }

    private function getMessageText(): string
    {
        return $this->getUpdate()->getMessage()->getText();
    }

    /**
     * @throws UnexpectedRequestException
     */
    private function checkRequest()
    {
        $allPolicyFields = count($this->policyFields);
        $filledPolicyFields = $this->getCountFilledPolicyFields();
        if ($filledPolicyFields >= $allPolicyFields) {
            throw new UnexpectedRequestException();
        }
    }

    /**
     * @return int
     */
    private function getCountFilledPolicyFields(): int
    {
        if (!isset($this->countFilledPolicyFields)) {
            $user = $this->getUser();
            /** @var MessageRepository $messageRepository */
            $messageRepository = $this->entityManager->getRepository(Message::class);
            $this->countFilledPolicyFields = $messageRepository->countLastAction($user);
        }

        return $this->countFilledPolicyFields;
    }

    private function getMessageField(): string
    {
        if ($this->isLastFieldRequest()) {
            return "";
        }

        $filledPolicyFields = $this->getCountFilledPolicyFields();
        $messageField = $this->policyFields[$filledPolicyFields + 1];

        return $this->translator->trans($this->messagePrefix . $messageField);
    }

    private function isLastFieldRequest(): bool
    {
        $filledPolicyFields = $this->getCountFilledPolicyFields();
        $allPolicyFields = count($this->policyFields);

        return $filledPolicyFields + 1 === $allPolicyFields;
    }

    private function getUser(): User
    {
        $chat = $this->getUpdate()->getMessage()->getChat();
        $userRepository = $this->entityManager->getRepository(User::class);

        return $userRepository->find($chat->getId());
    }
}
