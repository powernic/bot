<?php

namespace Powernic\Bot\Emias\API\Service;

use Exception;
use Graze\GuzzleHttp\JsonRpc\Client;
use JsonMapper;
use JsonMapper_Exception;
use LengthException;
use Powernic\Bot\Emias\API\Entity\AvailableResourceScheduleInfoDto;
use Powernic\Bot\Emias\API\Entity\Doctor;
use Powernic\Bot\Emias\API\Entity\DoctorInfoDto;
use Powernic\Bot\Emias\API\Entity\ScheduleInfoDto;
use Powernic\Bot\Emias\API\Entity\SpecialityInfoDto;
use Powernic\Bot\Emias\Entity\DoctorInfo;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Emias\Exception\RpcResponseException;
use Powernic\Bot\Emias\Policy\Entity\Policy;

class EmiasApiService
{

    private string $apiUrl;

    public function __construct(
        string $apiUrl = ''
    ) {
        $this->apiUrl = $apiUrl;
    }


    /**
     * @param Policy $policy
     * @param int $specialityId
     * @return DoctorInfoDto[]
     */
    public function getDoctorsInfoCollection(Policy $policy, int $specialityId): array
    {
        $client = Client::factory($this->apiUrl, ['rpc_error' => true]);
        $request = $client->request(
            1,
            'getDoctorsInfo',
            [
                'birthDate' => $policy->getDate()->format("Y-m-d"),
                "omsNumber" => $policy->getCode(),
                "specialityId" => $specialityId
            ]
        );

        $response = $client->send($request);
        $rpcResult = json_decode((string)$response->getBody());
        if (empty($rpcResult->result)) {
            throw new RpcResponseException();
        }
        $mapper = new JsonMapper();
        return $mapper->mapArray($rpcResult->result, [], DoctorInfoDto::class);
    }


    public function getSpecialitiesInfo(Policy $policy): array
    {
        $client = Client::factory($this->apiUrl, ['rpc_error' => true]);
        $request = $client->request(
            1,
            'getSpecialitiesInfo',
            [
                'birthDate' => $policy->getDate()->format("Y-m-d"),
                "omsNumber" => $policy->getCode()
            ]
        );

        $message = $client->send($request);
        $rpcResult = json_decode((string)$message->getBody());
        $mapper = new JsonMapper();

        return $mapper->mapArray($rpcResult->result, [], SpecialityInfoDto::class);
    }

    /**
     * @param Policy $policy
     * @param Speciality $speciality
     * @return ScheduleInfoDto[]
     * @throws LengthException|JsonMapper_Exception|Exception
     */
    public function getBatchScheduleInfo(Policy $policy, Speciality $speciality): array
    {
        $client = Client::factory($this->apiUrl, ['rpc_error' => true]);
        $request = $client->request(
            1,
            'getBatchScheduleInfo',
            [
                'birthDate' => $policy->getDate()->format("Y-m-d"),
                "omsNumber" => $policy->getCode(),
                "specialityId" => $speciality->getCode(),
            ]
        );

        $message = $client->send($request);
        $rpcResult = json_decode((string)$message->getBody());
        $mapper = new JsonMapper();

        return $mapper->mapArray($rpcResult->result, [], ScheduleInfoDto::class);
    }

    /**
     * @param Policy $policy
     * @param Doctor $doctor
     * @return AvailableResourceScheduleInfoDto
     */
    public function getAvailableResourceScheduleInfo(Policy $policy, Doctor $doctor): AvailableResourceScheduleInfoDto
    {
        $client = Client::factory($this->apiUrl, ['rpc_error' => true]);
        $request = $client->request(
            1,
            'getAvailableResourceScheduleInfo',
            [
                'birthDate' => $policy->getDate()->format("Y-m-d"),
                "omsNumber" => $policy->getCode(),
                "availableResourceId" => $doctor->getAvailableResourceId(),
                "complexResourceId" => $doctor->getComplexResourceId(),
            ]
        );

        $message = $client->send($request);
        $rpcResult = json_decode((string)$message->getBody());
        $mapper = new JsonMapper();

        return $mapper->map($rpcResult->result, new AvailableResourceScheduleInfoDto());
    }

}
