<?php

namespace Powernic\Bot\Framework\Form;

use InvalidArgumentException;

class FormRegistry
{
    /**
     * @var Form[]
     */
    private array $types = [];

    public function getType(string $name)
    {
        if (!isset($this->types[$name])) {
            if (!class_exists($name)) {
                throw new InvalidArgumentException(sprintf('Could not load type "%s": class does not exist.', $name));
            }
            if (!is_subclass_of($name, Form::class)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Could not load type "%s": class does not implement "Powernic\Bot\Framework\Form".',
                        $name
                    )
                );
            }

            $this->types[$name] = new $name();
        }

        return $this->types[$name];
    }
}
