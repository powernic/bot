<?php

namespace Emias\Service;

use LengthException;
use Powernic\Bot\Emias\Entity\ScheduleInfo;
use Powernic\Bot\Emias\Service\ScheduleInfoService;
use PHPUnit\Framework\TestCase;

class ScheduleInfoServiceTest extends TestCase
{
    public function testGetNearestScheduleInfo()
    {
        $scheduleInfoService = new ScheduleInfoService();
        $scheduleInfo1 = (new ScheduleInfo())->setStartTime("2021-11-20T15:48:00+03:00");
        $scheduleInfo2 = (new ScheduleInfo())->setStartTime("2021-12-20T16:48:00+03:00");
        $scheduleInfo3 = (new ScheduleInfo())->setStartTime("2021-12-20T16:48:00+03:00");
        $scheduleInfo4 = (new ScheduleInfo())->setStartTime("2021-12-20T17:48:00+03:00");
        $scheduleInfoCollection = [$scheduleInfo1, $scheduleInfo2, $scheduleInfo3, $scheduleInfo4];
        $this->assertSame(
            $scheduleInfo1,
            $scheduleInfoService->getNearestScheduleInfo($scheduleInfoCollection)
        );
    }

    public function testGetNearestScheduleInfoInConcreteDay()
    {
        $scheduleInfoService = new ScheduleInfoService();
        $targetDay = new \DateTimeImmutable("2021-12-20T11:00:00+03:00");
        $scheduleInfo1 = (new ScheduleInfo())->setStartTime("2021-11-20T15:48:00+03:00");
        $scheduleInfo2 = (new ScheduleInfo())->setStartTime("2021-12-20T16:48:00+03:00");
        $scheduleInfo3 = (new ScheduleInfo())->setStartTime("2021-12-20T16:48:00+03:00");
        $scheduleInfo4 = (new ScheduleInfo())->setStartTime("2021-12-20T17:48:00+03:00");
        $scheduleInfoCollection = [$scheduleInfo1, $scheduleInfo2, $scheduleInfo3, $scheduleInfo4];
        $this->assertSame(
            $scheduleInfo2,
            $scheduleInfoService->getNearestScheduleInfoInConcreteDay($targetDay, $scheduleInfoCollection)
        );
    }

    public function testItDoesNotAllowToGetNearestScheduleInfoFromEmptyCollection()
    {
        $this->expectException(LengthException::class);
        $scheduleInfoService = new ScheduleInfoService();
        $scheduleInfoService->getNearestScheduleInfo([]);
    }

    public function testItDoesNotAllowToGetNearestScheduleInfoInConcreteDayFromEmptyCollection()
    {
        $this->expectException(LengthException::class);
        $scheduleInfoService = new ScheduleInfoService();
        $targetDay = new \DateTimeImmutable("2021-12-20T11:00:00+03:00");
        $scheduleInfoService->getNearestScheduleInfoInConcreteDay($targetDay, []);
    }
}
