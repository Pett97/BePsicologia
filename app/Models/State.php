<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;
use Lib\Validations;

class State extends Model
{
    protected static string $table = "states";
    protected static array $columns = ['name'];

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
    }
}
