<?php

namespace Database\Populate;

use App\Models\State;
use App\Models\City;
use App\Models\Insurance;

class SupplyPopulate
{
    public static function populate(): void
    {
        $SupllyPopulate = 7;
        for ($i = 1; $i < $SupllyPopulate; $i++) {
            $state = new State(name: "Estado" . $i);
            $state->save();
            $city = new City(idState: $i, name: "Cidade" . $i);
            $city->save();
            $insurance = new Insurance(name: "Convenio" . $i);
            $insurance->save();
        }

        echo "SupplyPopulate Populate With $SupllyPopulate"."\n";
    }
}
