<?php

namespace Emias\Entity;

use GuzzleHttp\Psr7\Response;
use JsonMapper_Exception;
use PHPUnit\Framework\TestCase;
use Powernic\Bot\Emias\API\Entity\DoctorCollection;
use Powernic\Bot\Emias\API\Entity\ResourceDto;
use Powernic\Bot\Emias\Entity\Doctor;
use Powernic\Bot\Emias\Entity\DoctorInfo;
use Powernic\Bot\Emias\Exception\RpcResponseException;

class DoctorInfoCollectionTest extends TestCase
{

    public function testCorrectResponseContainsDoctorInfoTypes()
    {
        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode((object)['result' => [['id' => 1]]])
        );
        $this->assertInstanceOf(DoctorInfo::class, DoctorCollection::fromResponse($response)->first());
    }

    public function testIncorrectResponseShouldReturnException()
    {
        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['result' => ''])
        );
        $this->expectException(JsonMapper_Exception::class);
        $this->expectException(RpcResponseException::class);
        $this->assertInstanceOf(DoctorInfo::class, DoctorCollection::fromResponse($response)->first());
    }

    public function testDoctorIsAvailableWhenExistComplexResource()
    {
        $employeeId = 123;
        $mainDoctor = $this->createMock(Doctor::class);
        $doctorInfo = $this->createMock(DoctorInfo::class);
        $resource = $this->createMock(ResourceDto::class);
        $complexResource = [$resource];
        $mainDoctor->method('getEmployeeId')->willReturn($employeeId);
        $doctorInfo->method('getMainDoctor')->willReturn($mainDoctor);
        $doctorInfo->method('getComplexResource')->willReturn($complexResource);
        $doctorInfoCollection = new DoctorCollection([$doctorInfo]);
        $this->assertSame(true, $doctorInfoCollection->isAvailable($employeeId));
    }

    public function testDoctorIsUnAvailableWhenNotExistComplexResource()
    {
        $employeeId = 123;
        $mainDoctor = $this->createMock(Doctor::class);
        $doctorInfo = $this->createMock(DoctorInfo::class);
        $mainDoctor->method('getEmployeeId')->willReturn($employeeId);
        $doctorInfo->method('getMainDoctor')->willReturn($mainDoctor);
        $doctorInfoCollection = new DoctorCollection([$doctorInfo]);
        $this->assertSame(false, $doctorInfoCollection->isAvailable($employeeId));
    }

    public function DoctorIsNotAvailableWhenNotExistInDoctorCollection()
    {
        $employeeId = 123;
        $mainDoctor = $this->createMock(Doctor::class);
        $doctorInfo = $this->createMock(DoctorInfo::class);
        $mainDoctor->method('getEmployeeId')->willReturn($employeeId);
        $doctorInfo->method('getMainDoctor')->willReturn($mainDoctor);
        $doctorInfoCollection = new DoctorCollection([$doctorInfo]);
        $this->assertSame(false, $doctorInfoCollection->isAvailable(122));
    }
}
