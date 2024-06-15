<?php

namespace Database\Populate;

use App\Models\Client;
use App\Models\Appointment;
use App\Models\User; 

class AppointmentsPopulate
{
    public static function populate()
    {
        $client = new Client(
            name: 'ClienteAgendamento',
            phone: '42988853477',
            insurance_id: 2,
            streetName: 'Rua teste',
            numberHouse: 123,
            city_id: 1
        );
        $client->save();

        $adminUser = new User(
            name: 'AdminAgendamento',
            email: 'admin@example.2com',
            password: 'admin123',
            password_confirmation: 'admin123',
            city_id: 1
        );
        $adminUser->save();


        $test = new Appointment(
            userID: 2,
            date: new \DateTime("2024-06-15"),
            startHour: new \DateTime("2024-06-15 13:00:00"),
            periodHours: 4,
            clientID: 1
        );
        $test->save();

        echo "Appointment populated with 1 record\n";
    }
}
