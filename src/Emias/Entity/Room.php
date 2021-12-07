<?php

namespace Powernic\Bot\Emias\Entity;

class Room
{
    private string $id;
    private string $number;
    private int $lpuId;
    private string $lpuShortName;
    private int $addressPointId;
    private string $defaultAddress;

    /**
     * @var \DateTimeImmutable
     */
    private string $availabilityDate;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Room
     */
    public function setId(string $id): Room
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return Room
     */
    public function setNumber(string $number): Room
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return int
     */
    public function getLpuId(): int
    {
        return $this->lpuId;
    }

    /**
     * @param int $lpuId
     * @return Room
     */
    public function setLpuId(int $lpuId): Room
    {
        $this->lpuId = $lpuId;
        return $this;
    }

    /**
     * @return string
     */
    public function getLpuShortName(): string
    {
        return $this->lpuShortName;
    }

    /**
     * @param string $lpuShortName
     * @return Room
     */
    public function setLpuShortName(string $lpuShortName): Room
    {
        $this->lpuShortName = $lpuShortName;
        return $this;
    }

    /**
     * @return int
     */
    public function getAddressPointId(): int
    {
        return $this->addressPointId;
    }

    /**
     * @param int $addressPointId
     * @return Room
     */
    public function setAddressPointId(int $addressPointId): Room
    {
        $this->addressPointId = $addressPointId;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultAddress(): string
    {
        return $this->defaultAddress;
    }

    /**
     * @param string $defaultAddress
     * @return Room
     */
    public function setDefaultAddress(string $defaultAddress): Room
    {
        $this->defaultAddress = $defaultAddress;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getAvailabilityDate(): \DateTimeImmutable
    {
        return $this->availabilityDate;
    }

    /**
     * @param string $availabilityDate
     * @return Room
     */
    public function setAvailabilityDate(string $availabilityDate): Room
    {
        $this->availabilityDate = $availabilityDate;
        return $this;
    }
    
}
