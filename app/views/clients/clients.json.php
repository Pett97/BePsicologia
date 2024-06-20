<?php

foreach ($brands as $brand) {
    $json[] = ["id" => $brand->getID(), "name" => $brand->getName()];
}
