<?php

namespace App\Models;

use App\Services\ProfileAvatar;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use PDO;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $encrypted_password
 */
class Master extends Model
{
    protected static string $table = 'masters';
    protected static array $columns = ['name', 'email', 'encrypted_password'];

    protected ?string $password = null;
    protected ?string $password_confirmation = null;

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

    public static function findByEmail(string $email): Master|null
    {
        return Master::findBy(['email' => $email]);
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
