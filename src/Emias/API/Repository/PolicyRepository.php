<?php

namespace Powernic\Bot\Emias\API\Repository;

use Powernic\Bot\Emias\Policy\Entity\Policy;

class PolicyRepository extends EmiasRepository
{
    public function find(Policy $policy): ?Policy
    {
        try {
            $specialitiesInfo = $this->apiService->getSpecialitiesInfo($policy);
            if (empty($specialitiesInfo)) {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
        return $policy;
    }
}
