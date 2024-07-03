<?php

namespace Lib\Authentication;

use App\Models\User;
use App\Models\Master;

class Auth
{
    public static function login($user): void
    {
        $_SESSION['user']['id'] = $user->id;
        $_SESSION['user']['type'] = $user instanceof Master ? 'master' : 'user';
    }

    public static function user(): User|Master|null
    {
        if (isset($_SESSION['user']['id'])) {
            $id = $_SESSION['user']['id'];
            return $_SESSION['user']['type'] === 'master' ? Master::findById($id) : User::findById($id);
        }

        return null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']['id']) && self::user() !== null;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']['id']);
        unset($_SESSION['user']['type']);
    }

    public static function isMaster(): bool
    {
        return isset($_SESSION['user']['type']) && $_SESSION['user']['type'] === 'master';
    }
}
