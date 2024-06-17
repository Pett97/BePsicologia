<?php

namespace Tests\Unit\Models;

use App\Models\FixedSchedule;
use DateTime;
use PHPUnit\Framework\TestCase;

class FixedScheduleTest extends TestCase
{
    public function test_can_get_UserID(): void
    {
        $fixed = new FixedSchedule(
            userID: 2,
            dayOFWeek: 2,
            startTime: new \DateTime("2024-06-15 13:00:00"),
            endTime: new \DateTime("2024-06-15 17:00:00")
        );

        $this->assertEquals(2, $fixed->getUserID());
    }

    public function test_can_set_start_time(): void
    {
        $fixed = new FixedSchedule(
            userID: 2,
            dayOFWeek: 2,
            startTime: new \DateTime("2024-06-15 13:00:00"),
            endTime: new \DateTime("2024-06-15 17:00:00")
        );

        $fixed->setStartTime(new \DateTime("2024-09-15 17:00:00"));

        $this->assertEquals(
            "2024-09-15 17:00:00",
            $fixed->getStartTime()->format('Y-m-d H:i:s')
        );
    }

    public function test_dont_create_whitout_time(): void
    {
        $fixed = new FixedSchedule(
            userID: 2,
            dayOFWeek: 2,
            startTime: new \DateTime(""),
            endTime: new \DateTime("")
        );

        $this->assertFalse($fixed->hasErrors());
    }
}
