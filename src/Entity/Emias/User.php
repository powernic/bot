<?php

namespace Powernic\Bot\Entity\Emias;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity()
 * @Table(name="emias_user")
**/
class User
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @Column(type="integer")
     */
    private $code;

    /**
     * @Column(type="date")
     */
    private $date;

    /**
     * @param mixed $code
     * @return User
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return User
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }
}
