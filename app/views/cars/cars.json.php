<?php

foreach ($cars as $car) {
    $json[] = ["id" => $car->getID(), "name" => $car->getName()];
}
