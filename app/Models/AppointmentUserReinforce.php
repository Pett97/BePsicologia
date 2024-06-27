<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property int $appointments_id
 * @property int $psychologist_id
 */
class AppointmentUserReinforce extends Model
{
    protected static string $table = "appointments";
    protected static array $columns = ['psychologist_id','date','start_time','end_time','client_id' ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'psychologist_id');
    }

    public function problem(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointments_id');
    }

    public function validates(): void
    {
        Validations::uniqueness(['appointments_id', 'psychologist_id'], $this);
    }
}
