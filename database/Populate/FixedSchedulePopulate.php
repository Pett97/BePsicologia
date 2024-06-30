<?php

namespace Database\Populate;


use App\Models\FixedSchedule;


class FixedSchedulePopulate
{
    public static function populate()
    {

        for ($i = 1; $i < 4; $i++) {
            $test = [
                'psychologist_id' => $i,
                'day_of_week' => 4,
                'start_time' => "2024-04-04 13:00:" . $i,
                'end_time' => "2024-04-04 19:00:" . $i,
            ];

            $fixedSchedule = new FixedSchedule($test);
            $fixedSchedule->save();
        }

        echo "FixedSchedule populated with 4 record \n";
    }
}
