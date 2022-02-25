<?php

namespace Powernic\Bot\Chat\Entity;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

/** @Embeddable */
class Action
{
    /**
     * @Column(type="datetime", name="action_time" )
     */
    private DateTime $time;

    /**
     * @Column(type="string", name="action_code" )
     */
    private string $code;

    public function __construct(DateTime $time, string $code)
    {
        $this->time = $time;
        $this->code = $code;
    }

    /**
     * @return DateTime
     */
    public function getTime(): DateTime
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

}
