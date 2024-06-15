<?php
require __DIR__ . '/../../config/bootstrap.php';

use App\Models\Appointment;
use Core\Database\Database;
use Database\Populate\ClientsPopulate;
use Database\Populate\SupplyPopulate;
use Database\Populate\UsersPopulate;
use Database\Populate\AppointmentsPopulate;

Database::migrate();
//sempre popular as tabelas que não precisam ter relaçao antes 
SupplyPopulate::populate();
UsersPopulate::populate();
ClientsPopulate::populate();
AppointmentsPopulate::populate();
