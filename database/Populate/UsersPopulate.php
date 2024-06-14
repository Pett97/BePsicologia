<?php

namespace Database\Populate;

use App\Models\User;

class UsersPopulate
{
    public static function populate()
    {
        
        $adminUser = new User(
            name: 'Admin',
            email: 'admin@example.com',
            password: 'admin123',
            password_confirmation: 'admin123',
            city_id: 1   
        );
        $adminUser->save();

        
        $numberOfUsers = 4;

        for ($i = 1; $i <= $numberOfUsers; $i++) {
            $user = new User(
                name: 'Fulano ' . $i,
                email: 'fulano' . $i . '@example.com',
                password: '123456',
                password_confirmation: '123456',
                city_id: 2   
            );
            $user->save();
        }

        echo "Users populated with $numberOfUsers registers\n";
    }
}
