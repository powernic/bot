<?php

namespace Powernic\Bot\Framework\Form;

use Powernic\Bot\Framework\Exception\UnexpectedRequestException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TelegramBot\Api\Types\Message;

abstract class Form
{
    protected FieldCollection $fieldCollection;
    protected ?int $countFilledFields = null;
    private ValidatorInterface $validator;
    private string $entityClass;
    private Message $message;
    private bool $isFirstFieldRequest;

    public function __construct(ValidatorInterface $validator, string $entityClass)
    {
        $this->validator = $validator;
        $this->entityClass = $entityClass;
    }

    public function handleRequest(): string
    {
        $countFilledFields = $this->getCountFilledFields();
        $field = $this->fieldCollection->get($countFilledFields);
        $value = $this->message->getText();
        $errors = $this->validator->validatePropertyValue($this->entityClass, $field->getName(), $value);
        $hasError = count($errors) > 0;
        if ($hasError) {
            throw new ValidationFailedException($value, $errors);
        }

        return $this->getFieldMessage();
    }

    /**
     * @return int
     */
    abstract protected function getCountFilledFields(): int;

    abstract protected function configureFields(FieldCollection $fieldCollection): void;


    public function setRequest(Message $message, bool $isFirstFieldRequest = false): self
    {
        $this->message = $message;
        $this->isFirstFieldRequest = $isFirstFieldRequest;
        $this->fieldCollection = new FieldCollection();
        $this->configureFields($this->fieldCollection);

        return $this;
    }

    /**
     * @throws UnexpectedRequestException
     */
    public function validate(): self
    {
        $countAllFields = $this->fieldCollection->count();
        $countFilledFields = $this->getCountFilledFields();
        if ($countFilledFields >= $countAllFields) {
            throw new UnexpectedRequestException();
        }

        return $this;
    }

    public function isLastFieldRequest(): bool
    {
        $countAllFields = $this->fieldCollection->count();
        $countFilledFields = $this->getCountFilledFields();

        return $countFilledFields + 1 === $countAllFields;
    }

    public function isFirstFieldRequest(): bool
    {
        return $this->isFirstFieldRequest;
    }

    protected function getFieldMessage(): string
    {
        if ($this->isLastFieldRequest()) {
            return "";
        }

        if ($this->isFirstFieldRequest()) {
            return $this->fieldCollection->get(0)->getMessage();
        }

        $countFilledFields = $this->getCountFilledFields();
        $field = $this->fieldCollection->get($countFilledFields + 1);

        return $field->getMessage();
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }
}
