<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;
use Lib\Validations;

class FixedSchedule extends Model
{
    protected static string $table = "fixed_schedules";
    protected static array $columns =["psychologist_id","day_of_week","start_time","end_time"];

    public function validates(): void
    {
        Validations::notEmpty('psychologist_id', $this);
        Validations::notEmpty('day_of_week', $this);
        Validations::notEmpty('start_time', $this);
        Validations::notEmpty('end_time', $this);
    }
}
    
