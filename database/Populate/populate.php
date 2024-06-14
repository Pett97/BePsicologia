<?php
require __DIR__ . '/../../config/bootstrap.php';
use Core\Database\Database;
use Database\Populate\BrandsPopulate;
use Database\Populate\CarsPopulate;
use Database\Populate\UsersPopulate;

Database::migrate();


BrandsPopulate::populate();
CarsPopulate::populate();
UsersPopulate::populate();
