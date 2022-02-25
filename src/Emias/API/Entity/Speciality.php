<?php

namespace Powernic\Bot\Emias\API\Entity;

class Speciality
{

    private int $code;
    private string $name;

    public function __construct(string $name, int $code)
    {
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

}
