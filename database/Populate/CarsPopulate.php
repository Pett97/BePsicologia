<?php

namespace Database\Populate;

use App\Models\Car;

    class CarsPopulate{
        public static function populate():void{
            $numberOfBrands = 100;
            for ($i=0; $i < $numberOfBrands; $i++) { 
                $car = new Car(name:"Carro".$i);
                $car->save();
            }
            echo "Cars Populate With $numberOfBrands";
        }

    }
?>