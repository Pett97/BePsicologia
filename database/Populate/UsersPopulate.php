<?php

namespace Database\Populate;

use App\Models\User;
use App\Models\Master;

class UsersPopulate
{
    public static function populate()
    {
        $masterUser=[
            'name' => 'Master',
            'email' => 'master@example.com',
            'password' => 'master123',
            'password_confirmation' => 'master123',
        ];
        $master = new Master($masterUser);
        $master->save();

        $adminUser = [
            'name' => 'Bianca Teste',
            'email' => 'admin@example.com',
            'password' => 'admin123',
            'password_confirmation' => 'admin123',
            'city_id' => 1
        ];

        $user = new User($adminUser);
        $user->save();
        for ($i = 1; $i < 7; $i++) {
            $testUser = [
                'name' => 'Admin'.$i,
                'email' => 'admin'.$i.'@example.com',
                'password' => 'admin123',
                'password_confirmation' => 'admin123',
                'city_id' => 1
            ];

            $user = new User($testUser);
            $user->save();
        }
    }
}
