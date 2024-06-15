<?php

namespace Database\Populate;

use App\Models\Client;

class ClientsPopulate
{
    public static function populate()
    {
        
        $client = new Client(
            name: 'ClientTeste',
            phone: '42988853477',
            insurance_id: 2,
            streetName: 'Rua teste',
            numberHouse: 123,
            city_id: 1   
        );
        $client->save();

        
        $numberOfClients = 4;

        for ($i = 1; $i <= $numberOfClients; $i++) {
            $client = new Client(
                name: 'TestCliente',
                phone: '42988853477',
                insurance_id: 1,
                streetName: 'Rua teste',
                numberHouse: 1,
                city_id: 2   
            );
            $client->save();
        }

        echo "Clients populated with $numberOfClients registers\n";
    }
}
