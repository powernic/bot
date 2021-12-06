<?php

namespace Powernic\Bot\Emias\Entity;

class ReceptionType
{
    private string $code;
    private string $name;
    private string $primary;
    private string $home;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return ReceptionType
     */
    public function setCode(string $code): ReceptionType
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ReceptionType
     */
    public function setName(string $name): ReceptionType
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrimary(): string
    {
        return $this->primary;
    }

    /**
     * @param string $primary
     * @return ReceptionType
     */
    public function setPrimary(string $primary): ReceptionType
    {
        $this->primary = $primary;
        return $this;
    }

    /**
     * @return string
     */
    public function getHome(): string
    {
        return $this->home;
    }

    /**
     * @param string $home
     * @return ReceptionType
     */
    public function setHome(string $home): ReceptionType
    {
        $this->home = $home;
        return $this;
    }
    
}
