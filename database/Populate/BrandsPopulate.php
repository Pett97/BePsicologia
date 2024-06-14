<?php

namespace Database\Populate;

use App\Models\Brand;

    class BrandsPopulate{
        public static function populate():void{
            $numberOfBrands = 100;
            for ($i=0; $i < $numberOfBrands; $i++) { 
                $brand = new Brand(name:"Marca".$i);
                $brand->save();
            }
            echo "Brands Populate With $numberOfBrands";
        }

    }
?>