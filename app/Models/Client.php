<?php

namespace App\Models;

use App\Services\ProfileAvatar;
use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

class Client extends Model
{
    protected static string $table = "clients";
    protected static array $columns = ['name', 'phone', 'insurance_id',
     'street_name', 'number', 'city_id', 'avatar_name'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }


    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('phone', $this);
        Validations::notEmpty('insurance_id', $this);
        Validations::notEmpty('street_name', $this);
        Validations::notEmpty('number', $this);
        Validations::notEmpty('city_id', $this);
    }

    public function avatar(): ProfileAvatar
    {
        return new ProfileAvatar($this);
    }
}
