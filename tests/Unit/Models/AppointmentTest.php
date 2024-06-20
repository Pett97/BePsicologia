<?php

namespace Tests\Unit\Models;

use App\Models\Appointment;
use PHPUnit\Framework\TestCase;

class AppointmentTest extends TestCase
{
    public function test_create_valid_appointment(): void
    {
        $appointment = new Appointment(
            userID: 1,
            date: new \DateTime("2024-06-15"),
            startHour: new \DateTime("2024-06-15 13:00:00"),
            periodHours: 4,
            clientID: 1
        );
        $this->assertTrue($appointment->isValid());
    }
}
