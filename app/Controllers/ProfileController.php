<?php

namespace App\Controllers;

use App\Models\Client;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class ProfileController extends Controller
{
    public function show(): void
    {
        $title = 'Meu Perfil';
        $this->render('profile/show', compact('title'));
    }

    public function updateAvatar(): void
    {
        $image = $_FILES['user_avatar'];

        $this->current_user->avatar()->update($image);
        $this->redirectTo(route('profile.show'));
    }

    public function updateClientAvatar(Request $request): void
    {
        $referer = $_SERVER['HTTP_REFERER'];
        $path = parse_url($referer, PHP_URL_PATH);

        $id = $this->extractIdFromPath($path);
        $image = $_FILES['client_avatar'];
        $client = Client::findById($id);
        $client->avatar()->update($image);
        $this->redirectTo(route('clients.list'));
    }

    private function extractIdFromPath(string $path): ?int
    {

        preg_match('/\/client\/(\d+)/', $path, $matches);


        if (isset($matches[1])) {
            return (int) $matches[1];
        }

        return null;
    }
}
