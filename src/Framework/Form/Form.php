<?php

namespace Powernic\Bot\Framework\Form;

use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Framework\Exception\UnexpectedRequestException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TelegramBot\Api\Types\Update;

abstract class Form
{
    protected FieldCollection $fieldCollection;
    protected Update $update;
    protected ?int $countFilledFields = null;
    private ValidatorInterface $validator;


    public function handleRequest(): string
    {
        $countfilledFields = $this->getCountFilledFields();
        $field = $this->fieldCollection[$countfilledFields];
        $value = $this->getMessageText();
        $errors = $this->validator->validatePropertyValue(Policy::class, $field->getName(), $value);
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

    abstract protected function configureForm(FieldCollection $fieldCollection): void;


    public function setRequest(Update $update): self
    {
        $this->update = $update;
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

    /**
     * @return Update
     */
    public function getUpdate(): Update
    {
        return $this->update;
    }

    protected function getMessageText(): string
    {
        return $this->getUpdate()->getMessage()->getText();
    }

    public function isLastFieldRequest(): bool
    {
        $countAllFields = $this->fieldCollection->count();
        $countFilledFields = $this->getCountFilledFields();

        return $countFilledFields + 1 === $countAllFields;
    }


    protected function getFieldMessage(): string
    {
        if ($this->isLastFieldRequest()) {
            return "";
        }

        $countFilledFields = $this->getCountFilledFields();
        $field = $this->fieldCollection[$countFilledFields + 1];

        return $field->getMessage();
    }
}