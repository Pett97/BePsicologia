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
        for ($i = 1; $i <= $SupllyPopulate; $i++) {
            $dataState = [
                'name' => "Estado" . $i
            ];
            $dataCity = [
                'name' => "Cidade" . $i,
                'state_id' => $i
            ];
            $dataInsurance = [
                'name' => "Convenio" . $i
            ];
            $state = new State($dataState);
            $state->save();
            $city = new City($dataCity);
            $city->save();
            $insurnace = new Insurance($dataInsurance);
            $insurnace->save();
        }

        echo "SupplyPopulate Populate With $SupllyPopulate" . "\n";
    }
}
