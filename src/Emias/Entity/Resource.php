<?php

namespace Powernic\Bot\Emias\Entity;

class Resource
{
    private int $id;
    private string $name;
    private Room $room;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Resource
     */
    public function setId(int $id): Resource
    {
        $this->id = $id;
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
     * @return Resource
     */
    public function setName(string $name): Resource
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Room
     */
    public function getRoom(): Room
    {
        return $this->room;
    }

    /**
     * @param Room $room
     * @return Resource
     */
    public function setRoom(Room $room): Resource
    {
        $this->room = $room;
        return $this;
    }
    
}
