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
class FixedScheduleUserReinforce extends Model
{
    protected static string $table = "fixed_schedules";
    protected static array $columns = ["psychologist_id","day_of_week","start_time","end_time"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'psychologist_id');
    }

    public function fixedsSchedules(): BelongsTo
    {
        return $this->belongsTo(FixedSchedule::class, 'fixed_schedules_id');
    }

    public function validates(): void
    {
        Validations::uniqueness(['appointments_id', 'psychologist_id'], $this);
    }
}
