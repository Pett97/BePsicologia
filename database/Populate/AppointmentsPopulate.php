<?php

namespace Database\Populate;

use App\Models\Client;
use App\Models\Appointment;
use App\Models\User; 

class AppointmentsPopulate
{
    public static function populate()
    {
    
        for ($i=0; $i < 2; $i++) { 
            $test = new Appointment(
                userID: 2,
                date: new \DateTime("2024-06-15"),
                startHour: new \DateTime("2024-06-15 13:00:00"),
                endHour: new \DateTime("2024-06-15 17:32:00"),
                clientID: 1
            );
            $test->save();
        }

        echo "Appointment populated with 1 record\n";
    }
}
