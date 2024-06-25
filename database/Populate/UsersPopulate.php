<?php

namespace Database\Populate;

use App\Models\User;

class UsersPopulate
{
    public static function populate()
    {
        $adminUser = new User(
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => 'admin123',
                'password_confirmation' => 'admin123',
                'city_id' => 1
            ]
        );

        $user = new User($adminUser);
        $user->save();

        $numberOfUsers = 10;

        for ($i = 1; $i < $numberOfUsers; $i++) {
            $data =  [
                'name' => 'Fulano ' . $i,
                'email' => 'fulano' . $i . '@example.com',
                'password' => '123456',
                'password_confirmation' => '123456',
                'city_id' => 1
            ];

            $user = new User($data);
            $user->save();
        }

        echo "Users populated with $numberOfUsers registers\n";
    }
}
