<?php

namespace Powernic\Bot\Framework\Chat\Calendar;

class Button
{
    private string $text;
    private int $value;

    public function __construct(string $text, int $value)
    {
        $this->text = $text;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
