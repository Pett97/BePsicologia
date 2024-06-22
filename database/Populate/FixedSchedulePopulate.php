<?php

namespace Database\Populate;

use App\Models\City;
use App\Models\FixedSchedule;
use App\Models\State;
use App\Models\User;

class FixedSchedulePopulate
{
    public static function populate()
    {

        for ($i = 0; $i < 2; $i++) {
            $test = new FixedSchedule(
                userID: 1,
                dayOFWeek: $i,
                startTime: new \DateTime("2024-06-15 13:00:00"),
                endTime: new \DateTime("2024-06-15 17:00:00")
            );
            $test->save();
        }

        echo "FixedSchedule populated with 10 record\n";
    }
}
