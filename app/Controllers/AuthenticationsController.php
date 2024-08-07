<?php

namespace App\Controllers;

use App\Models\Master;
use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AuthenticationsController extends Controller
{
    public function new(): void
    {
        $this->render('authentications/new');
    }

    public function authenticate(Request $request): void
    {
        $params = $request->getParam('user');
        $user = User::findByEmail($params['email']);
        $master = Master::findByEmail($params['email']);

        if ($master && $master->authenticate($params['password'])) {
            Auth::login($master);
            FlashMessage::success('Login realizado com sucesso!');
            $this->redirectTo(route('list.appointaments'));
        } elseif ($user && $user->authenticate($params['password'])) {
            Auth::login($user);
            FlashMessage::success('Login realizado com sucesso!');
            $this->redirectTo(route('list.appointaments'));
        } else {
            FlashMessage::danger('Email e/ou senha inválidos!');
            $this->redirectTo(route('users.login'));
        }
    }

    public function destroy(): void
    {
        Auth::logout();
        FlashMessage::success('Logout realizado com sucesso!');
        $this->redirectTo(route('users.login'));
    }
}
