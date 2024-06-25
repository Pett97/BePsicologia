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
 * @property string $encrypted_password
 * @property Appointment[] $appointments
 * @property Appointment[] $reinforced_appointments
 */
class User extends Model
{
    protected static string $table = 'users';
    protected static array $columns = ['name', 'email', 'password','city_id'];

    protected ?string $password = null;
    protected ?string $password_confirmation = null;

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'psychologist_id');
    }

    public function reinforcedAppointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class, 'problem_user_reinforce', 'psychologist_id', 'appointment_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('email', $this);

        Validations::uniqueness('email', $this);

        if ($this->newRecord()) {
            Validations::passwordConfirmation($this);
        }
    }

    public function authenticate(string $password): bool
    {
        if ($this->password == null) {
            return false;
        }

        return password_verify($password, $this->password);
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
            $this->password = password_hash($value, PASSWORD_DEFAULT);
        }
    }

    
}
