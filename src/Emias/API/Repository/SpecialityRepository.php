<?php

namespace Powernic\Bot\Emias\API\Repository;

use Powernic\Bot\Emias\API\Entity\SpecialityCollection;
use Powernic\Bot\Emias\API\Service\EmiasApiService;
use Powernic\Bot\Emias\Policy\Repository\PolicyRepository;

class SpecialityRepository extends EmiasRepository
{
    private PolicyRepository $policyRepository;

    public function __construct(EmiasApiService $apiService, PolicyRepository $policyRepository)
    {
        parent::__construct($apiService);
        $this->policyRepository = $policyRepository;
    }

    public function findByUserPolicy(int $userId, int $policyId): SpecialityCollection
    {
        $policy = $this->policyRepository->findOneBy(['user' => $userId, 'id' => $policyId]);
        $specialityInfoDtoCollection = $this->apiService->getSpecialitiesInfo($policy);
        return SpecialityCollection::createFromArrayDto($specialityInfoDtoCollection);
    }
}
