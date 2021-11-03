<?php

namespace Powernic\Bot\CallbackHandler\Emias\Policy;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\QueryException;
use Exception;
use Powernic\Bot\CallbackHandler\AbstractCallbackHandler;
use Powernic\Bot\Entity\Chat\Message;
use Powernic\Bot\Entity\Chat\User;
use Powernic\Bot\Entity\Emias\Policy;
use Powernic\Bot\Exception\UnexpectedRequestException;
use Powernic\Bot\Repository\Chat\MessageRepository;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception as BotException;
use TelegramBot\Api\InvalidArgumentException;
use TelegramBot\Api\Types\CallbackQuery;

class AddCallbackHandler extends AbstractCallbackHandler
{
    private BotApi $bot;
    private EntityManager $entityManager;
    private array $policyFields = ["name", "code", "date"];
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
     * @throws BotException|InvalidArgumentException|ORMException|OptimisticLockException
     * @throws Exception
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
        $text = "Название полиса:";
        $this->bot->sendMessage(
            $chat->getId(),
            $text
        );
    }

    /**
     * @throws UnexpectedRequestException|Exception
     */
    public function textHandle(): void
    {
        parent::textHandle();

        $message = $this->getUpdate()->getMessage();
        $chat = $message->getChat();
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var ?User $user */
        $user = $userRepository->find($chat->getId());
        $allPolicyFields = count($this->policyFields);
        /** @var MessageRepository $messageRepository */
        $messageRepository = $this->entityManager->getRepository(Message::class);
        $filledPolicyFields = $messageRepository->countLastAction($user);
        if ($filledPolicyFields >= $allPolicyFields) {
            throw new UnexpectedRequestException();
        }
        $fieldName = $this->policyFields[$filledPolicyFields];
        $nextFieldName = $this->policyFields[$filledPolicyFields + 1];
        try {
            $responseMessage = $this->handleValue(
                $fieldName,
                $message->getText(),
                "emias.policy.add." . $nextFieldName
            );
            if ($filledPolicyFields + 1 == $allPolicyFields) {
                $this->addPolicy($user, $message->getText());
            }
            $this->addMessage($message, $user);
            $this->entityManager->flush();
        } catch (ValidationFailedException $e) {
            $responseMessage = $e->getViolations()->get(0)->getMessage();
        }
        $this->bot->sendMessage(
            $chat->getId(),
            $this->translator->trans($responseMessage)
        );
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $message
     * @return string
     * @throws ValidationFailedException
     */
    private function handleValue(string $name, string $value, string $message): string
    {
        $errors = $this->validator->validatePropertyValue(Policy::class, $name, $value);
        $hasError = count($errors) > 0;
        if ($hasError) {
            throw new ValidationFailedException($value, $errors);
        }

        return $message;
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
     * @throws Exception|OptimisticLockException|ORMException
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
     * @param User $user
     * @param string $date
     * @throws ORMException
     * @throws QueryException
     * @throws Exception
     */
    private function addPolicy(User $user, string $date): void
    {
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
    }

    /**
     * @param \TelegramBot\Api\Types\Message $message
     * @param User|null $user
     * @throws ORMException
     */
    private function addMessage(\TelegramBot\Api\Types\Message $message, ?User $user): void
    {
        $message = (new Message())
            ->setId($message->getMessageId())
            ->setUser($user)
            ->setActionCode($this->getName())
            ->setTime((new DateTime())->setTimestamp($message->getDate()))
            ->setText($message->getText());
        $this->entityManager->persist($message);
    }
}
