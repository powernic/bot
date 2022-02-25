<?php

namespace Powernic\Bot\Framework\Form;

class FieldCollection
{
    /**
     * @var Field[]
     */
    private array $fields;

    public function add(string $name, string $message): self
    {
        $this->fields[] = new Field($name, $message);

        return $this;
    }

    public function count(): int
    {
        return count($this->fields);
    }

    public function get(int $index): Field
    {
        return $this->fields[$index];
    }

    public function has(int $index): bool
    {
        return isset($this->fields[$index]);
    }
}
