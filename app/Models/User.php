<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsToMany;
use Core\Database\ActiveRecord\HasMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property Appointment[] $appointments
 * @property Appointment[] $reinforced_appointments
 */
class User extends Model
{
    protected static string $table = 'users';
    protected static array $columns = ['name', 'email', 'encrypted_password', 'city_id'];

    protected ?string $password = null;
    protected ?string $password_confirmation = null;

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'psychologist_id');
    }

    public function reinforcedAppointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class, 'appointment_user__reinforce', 'psychologist_id', 'appointment_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('email', $this);
        Validations::notEmpty('password', $this);
        Validations::uniqueness('email', $this);

        if ($this->newRecord()) {
            Validations::passwordConfirmation($this);
        }
    }

    public function authenticate(string $password): bool
    {
        if ($this->encrypted_password == null) {
            return false;
        }

        return password_verify($password, $this->encrypted_password);
    }

    public static function findByEmail(string $email): User|null
    {
        return User::findBy(['email' => $email]);
    }

    public function __set(string $property, mixed $value): void
    {
        parent::__set($property, $value);

        if (
            $property === 'password' &&
            $this->newRecord() &&
            $value !== null && $value !== ''
        ) {
            $this->encrypted_password = password_hash($value, PASSWORD_DEFAULT);
        }
    }
}
