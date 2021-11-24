<?php

namespace Powernic\Bot\Emias\Policy\Form;

use Powernic\Bot\Chat\Entity\User;
use Powernic\Bot\Chat\Repository\MessageRepository;
use Powernic\Bot\Chat\Repository\UserRepository;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Framework\Form\FieldCollection;
use Powernic\Bot\Framework\Form\Form;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class PolicyForm extends Form
{
    private MessageRepository $messageRepository;
    private UserRepository $userRepository;
    private ValidatorInterface $validator;

    public function __construct(
        ValidatorInterface $validator,
        MessageRepository $messageRepository,
        UserRepository $userRepository
    ) {
        $this->validator = $validator;
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
    }

    protected function configureFields($fieldCollection): void
    {
        $fieldCollection
            ->add('name', 'emias.policy.add.name')
            ->add('code', 'emias.policy.add.code')
            ->add('date', 'emias.policy.add.date');
    }

    /**
     * @return string
     * @throws ValidationFailedException
     */
    public function handleRequest(): string
    {
        $filledFields = $this->getCountFilledFields();
        $field = $this->fieldCollection[$filledFields];
        $value = $this->getMessageText();
        $errors = $this->validator->validatePropertyValue(Policy::class, $field->getName(), $value);
        $hasError = count($errors) > 0;
        if ($hasError) {
            throw new ValidationFailedException($value, $errors);
        }

        return $this->getFieldMessage();
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
        $chat = $this->getUpdate()->getMessage()->getChat();

        return $this->userRepository->find($chat->getId());
    }

    protected function configureForm(FieldCollection $fieldCollection): void
    {
        // TODO: Implement configureForm() method.
    }
}