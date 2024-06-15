<?php

namespace Database\Populate;

use App\Models\FixedSchedule;

class FixedSchedulePopulate
{
    public static function populate()
    {

        $test = new FixedSchedule(
            userID: 3,
            dayOFWeek: 4,
            startTime: new \DateTime("2024-06-15 13:00:00"),
            endTime: new \DateTime("2024-06-15 17:00:00")
        );
        $test->save();

        echo "FixedSchedule populated with 1 record\n";
    }
}
