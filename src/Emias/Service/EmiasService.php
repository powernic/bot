<?php

namespace Powernic\Bot\Emias\Service;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Graze\GuzzleHttp\JsonRpc\Client;
use Graze\GuzzleHttp\JsonRpc\Exception\RequestException;
use JsonMapper;
use JsonMapper_Exception;
use LengthException;
use Powernic\Bot\Emias\Entity\DoctorInfo;
use Powernic\Bot\Emias\Entity\ScheduleInfo;
use Powernic\Bot\Emias\Entity\Speciality;
use Powernic\Bot\Emias\Policy\Entity\Policy;
use Powernic\Bot\Emias\Policy\Repository\PolicyRepository;
use Powernic\Bot\Emias\Repository\SpecialityRepository;

final class EmiasService
{
    private string $apiUrl;
    private PolicyRepository $policyRepository;
    private EntityManagerInterface $entityManager;
    private ScheduleInfoService $scheduleInfoService;

    public function __construct(
        ScheduleInfoService $scheduleInfoService,
        PolicyRepository $policyRepository,
        SpecialityRepository $specialityRepository,
        EntityManagerInterface $entityManager,
        string $apiUrl = ''
    ) {
        $this->apiUrl = $apiUrl;
        $this->policyRepository = $policyRepository;
        $this->entityManager = $entityManager;
        $this->scheduleInfoService = $scheduleInfoService;
    }

    /**
     * @return Speciality[]
     * @throws RequestException
     * @throws JsonMapper_Exception
     */
    public function getSpecialitiesInfo(int $userId, int $policyId): array
    {
        $policy = $this->policyRepository->findOneBy(['user' => $userId, 'id' => $policyId]);
        $client = Client::factory($this->apiUrl, ['rpc_error' => true]);
        $request = $client->request(
            1,
            'getSpecialitiesInfo',
            ['birthDate' => $policy->getDate()->format("Y-m-d"), "omsNumber" => $policy->getCode()]
        );

        $message = $client->send($request);
        $rpcResult = json_decode((string)$message->getBody());
        $mapper = new JsonMapper();

        /** @var Speciality[] $specialities */
        $specialities = $mapper->mapArray($rpcResult->result, [], Speciality::class);
        $this->saveSpecialities($specialities);

        return $specialities;
    }

    /**
     * @param int $userId
     * @param int $policyId
     * @param Speciality $speciality
     * @return DoctorInfo[]
     * @throws JsonMapper_Exception
     */
    public function getDoctorsInfo(int $userId, int $policyId, Speciality $speciality): array
    {
        $policy = $this->policyRepository->findOneBy(['user' => $userId, 'id' => $policyId]);
        $client = Client::factory($this->apiUrl, ['rpc_error' => true]);
        $request = $client->request(
            1,
            'getDoctorsInfo',
            [
                'birthDate' => $policy->getDate()->format("Y-m-d"),
                "omsNumber" => $policy->getCode(),
                "specialityId" => $speciality->getCode()
            ]
        );

        $message = $client->send($request);
        $rpcResult = json_decode((string)$message->getBody());
        $mapper = new JsonMapper();

        /** @var DoctorInfo[] $doctorInfoCollection */
        $doctorInfoCollection = $mapper->mapArray($rpcResult->result, [], DoctorInfo::class);
        $this->saveSpecialities($doctorInfoCollection);

        return $doctorInfoCollection;
    }

    /**
     * @param Policy $policy
     * @param Speciality $speciality
     * @return ScheduleInfo
     * @throws LengthException|JsonMapper_Exception|Exception
     */
    public function getNearestScheduleInfo(Policy $policy, Speciality $speciality): ScheduleInfo
    {
        $scheduleInfoCollection = $this->getBatchScheduleInfo($policy, $speciality);
        return $this->scheduleInfoService->getNearestScheduleInfo($scheduleInfoCollection);
    }

    /**
     * @throws LengthException|JsonMapper_Exception|Exception
     */
    public function getNearestScheduleInfoInConcreteDay(
        Policy $policy,
        Speciality $speciality,
        DateTimeImmutable $targetDate
    ): ScheduleInfo {
        $scheduleInfoCollection = $this->getBatchScheduleInfo($policy, $speciality);
        return $this->scheduleInfoService->getNearestScheduleInfoInConcreteDay($targetDate, $scheduleInfoCollection);
    }

    /**
     * @param Policy $policy
     * @param Speciality $speciality
     * @return ScheduleInfo[]
     * @throws LengthException|JsonMapper_Exception|Exception
     */
    private function getBatchScheduleInfo(Policy $policy, Speciality $speciality): array
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

        return $mapper->mapArray($rpcResult->result, [], ScheduleInfo::class);
    }

    /**
     * @param Speciality[] $specialities
     */
    private function saveSpecialities(array $specialities)
    {
        foreach ($specialities as $speciality) {
            $specialityEntity = $this->entityManager->find(Speciality::class, $speciality->getCode());
            if ($specialityEntity) {
                $specialityEntity->setName($speciality->getName());
            } else {
                $this->entityManager->persist($speciality);
            }
        }

        $this->entityManager->flush();
    }

}
