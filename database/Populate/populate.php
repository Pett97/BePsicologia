<?php
require __DIR__ . '/../../config/bootstrap.php';



use Core\Database\Database;
use Database\Populate\ClientsPopulate;
use Database\Populate\SupplyPopulate;
use Database\Populate\UsersPopulate;

Database::migrate();
ClientsPopulate::populate();
SupplyPopulate::populate();
UsersPopulate::populate();
