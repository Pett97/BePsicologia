<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;
use Lib\Validations;

class City extends Model
{
    protected static string $table =  "citys";
    protected static array $columns = ['name','state_id'];

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('state_id', $this);
    }
}
