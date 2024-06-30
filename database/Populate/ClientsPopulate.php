<?php

namespace Database\Populate;

use App\Models\Client;

class ClientsPopulate
{
    public static function populate()
    {
        $numberOFClients = 15;
        for ($i = 0; $i <= $numberOFClients; $i++) {
            $testeClient = [
                'name' => "Cliente" . $i,
                'phone' => "022345678",
                'insurance_id' => 1,
                'street_name' => "nova brasilia",
                'number' => 285,
                'city_id' => 1
            ];

            $client = new Client($testeClient);
            $client->save();
        }

        echo " Clients Populate With " . $numberOFClients . "\n";
    }
}
