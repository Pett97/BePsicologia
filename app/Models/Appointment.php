<?php
namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\BelongsToMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

class Appointment extends Model
{
    protected static string $table = "appointments";
    protected static array $columns = ['psychologist_id', 'date', 'start_time', 'end_time', 'client_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'psychologist_id');
    }

    public function reinforcedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'problem_user_reinforce', 'appointment_id', 'psychologist_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('title', $this);
    }

    public function isSupportedByUser(User $user): bool
    {
        return AppointmentUserReinforce::exists(['appointment_id' => $this->id, 'user_id' => $user->id]);
    }
}
