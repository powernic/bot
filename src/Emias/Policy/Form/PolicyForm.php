<?php

namespace Powernic\Bot\Emias\Policy\Form;

use Powernic\Bot\Chat\Entity\User;
use Powernic\Bot\Chat\Repository\MessageRepository;
use Powernic\Bot\Chat\Repository\UserRepository;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Framework\Form\FieldCollection;
use Powernic\Bot\Framework\Form\Form;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class PolicyForm extends Form
{
    private MessageRepository $messageRepository;
    private UserRepository $userRepository;

    public function __construct(
        ValidatorInterface $validator,
        MessageRepository $messageRepository,
        UserRepository $userRepository
    ) {
        parent::__construct($validator, Policy::class);
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
    }

    protected function configureFields(FieldCollection $fieldCollection): void
    {
        $fieldCollection
            ->add('name', 'emias.policy.add.name')
            ->add('code', 'emias.policy.add.code')
            ->add('date', 'emias.policy.add.date');
    }

    protected function getCountFilledFields(): int
    {
        if (!isset($this->countFilledFields)) {
            $user = $this->getUser();
            $this->countFilledFields = $this->messageRepository->countLastAction($user);
        }

        return $this->countFilledFields;
    }

    private function getUser(): User
    {
        $chat = $this->getMessage()->getChat();

        return $this->userRepository->find($chat->getId());
    }
}
