<?php

namespace Powernic\Bot\Emias\API\Repository;

use Powernic\Bot\Emias\API\Entity\DoctorCollection;
use Powernic\Bot\Emias\API\Service\EmiasApiService;
use Powernic\Bot\Emias\Policy\Repository\PolicyRepository;

class DoctorRepository extends EmiasRepository
{

    private PolicyRepository $policyRepository;

    public function __construct(EmiasApiService $apiService, PolicyRepository $policyRepository)
    {
        parent::__construct($apiService);
        $this->policyRepository = $policyRepository;
    }

    /**
     * @param int $policyId
     * @param int $specialityId
     * @return DoctorCollection
     */
    public function findBySpeciality(int $policyId, int $specialityId): DoctorCollection
    {
        $policy = $this->policyRepository->find($policyId);
        $doctorInfoDtoCollection = $this->apiService->getDoctorsInfoCollection($policy, $specialityId);
        return DoctorCollection::createFromArrayDto($doctorInfoDtoCollection);
    }

}
