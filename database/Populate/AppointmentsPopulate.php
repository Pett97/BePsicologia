<?php

namespace Database\Populate;


use App\Models\Appointment;

use DateTime;

class AppointmentsPopulate
{
    public static function populate()
    {
        $numberOFAppointments = 7;
        for ($i = 1; $i < $numberOFAppointments; $i++) {
            $testAppointament = [
                'psychologist_id' => $i,
                'date' => "1997-04-04",
                'start_time' => "2024-04-04 13:00:".$i,
                'end_time' => "2024-04-04 14:00:31",
                'client_id' => $i
            ];
            $appointment = new Appointment($testAppointament);
            $appointment->save();
        };



        echo "Appointment populated with" . $numberOFAppointments . " \n";
    }
}
