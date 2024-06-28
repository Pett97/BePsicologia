<?php

namespace Database\Populate;

use App\Models\User;

class UsersPopulate
{
    public static function populate()
    {

        $adminUser = [
            'name' => 'Admin',
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
