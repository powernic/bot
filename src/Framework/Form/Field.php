<?php

namespace Powernic\Bot\Framework\Form;

class Field
{
    private string $name;
    private string $message;

    /**
     * @param string $name
     * @param string $message
     */
    public function __construct(string $name, string $message)
    {
        $this->name = $name;
        $this->message = $message;
    }


    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

}