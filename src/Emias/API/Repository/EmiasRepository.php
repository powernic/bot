<?php

namespace Powernic\Bot\Emias\API\Repository;

use Powernic\Bot\Emias\API\Service\EmiasApiService;

abstract class EmiasRepository
{
    protected EmiasApiService $apiService;

    public function __construct(EmiasApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function findAll()
    {
        return null;
    }

    public function findOneBy()
    {
        return null;
    }

    public function findBy()
    {
        return null;
    }
}
