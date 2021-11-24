<?php

namespace Powernic\Bot\Emias\Policy\CallbackHandler;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Emias\Policy\Form\PolicyForm;
use Powernic\Bot\Framework\Handler\Callback\CallbackHandler;
use Powernic\Bot\Chat\Entity\Message;
use Powernic\Bot\Chat\Entity\User;
use Powernic\Bot\Chat\Repository\MessageRepository;
use Powernic\Bot\Framework\Exception\UnexpectedRequestException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Contracts\Translation\TranslatorInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception as BotException;
use TelegramBot\Api\InvalidArgumentException;
use TelegramBot\Api\Types\CallbackQuery;

class AddCallbackHandler extends CallbackHandler
{
    private BotApi $bot;
    private EntityManager $entityManager;
    private TranslatorInterface $translator;
    private PolicyForm $form;

    public function __construct(
        BotApi $bot,
        EntityManager $entityManager,
        TranslatorInterface $translator,
        PolicyForm $form
    ) {
        $this->bot = $bot;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->form = $form;
    }

    /**
     * @throws BotException
     * @throws InvalidArgumentException
     * @throws OptimisticLockException
     */
    public function handle(): void
    {
        $query = $this->getCallbackQuery();
        $chat = $query->getMessage()->getChat();
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var ?User $user */
        $user = $userRepository->find($chat->getId());
        if (!$user) {
            $user = $this->createUser($query);
        }
        $this->setUserAction($user, $query);
        $responseMessage = $this->handleRequest();
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
            $responseMessage = $this->handleRequest();
            if ($this->form->isLastFieldRequest()) {
                $policy = $this->addPolicy($this->getMessageText());
                $responseMessage = $this->translator->trans(
                    "emias.policy.add.success",
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

    private function getUser(): User
    {
        $chat = $this->getUpdate()->getMessage()->getChat();
        $userRepository = $this->entityManager->getRepository(User::class);

        return $userRepository->find($chat->getId());
    }

    private function handleRequest(): string
    {
        return $this->form->setRequest($this->getUpdate())
            ->validate()
            ->handleRequest();
    }
}
