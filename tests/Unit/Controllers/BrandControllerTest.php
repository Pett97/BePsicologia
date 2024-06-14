<?php

namespace Tests\Unit\Controllers;

//require "/var/www/core/constants/general.php";


use App\Models\Brand;

class BrandControllerTest extends ControllerTestCase
{
    public function test_list_all_brands(): void
    {
        $brands[] = new Brand(name: "Marca Teste1");
        $brands[] = new Brand(name: "Marca Teste2");

        foreach ($brands as $brand) {
            $brand->save();
        }

        $response  = $this->get(action: "index", controller: "App\Controllers\BrandsController");



        foreach ($brands as $brand) {
            $this->assertMatchesRegularExpression("/{$brand->getName()}/", $response);
        }
    }
}
