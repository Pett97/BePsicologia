<?php

namespace App\Controllers;

use App\Models\FixedSchedule;
use Core\Http\Request;
use Lib\FlashMessage;
use App\Models\User;
use Lib\Authentication\Auth;


class FixedsSchedulesControllers
{
    private string $layout = "application";

    private ?User $currentUser = null;

    public function currentUser(): ?User
    {
        if ($this->currentUser === null) {
            $this->currentUser = Auth::user();
        }
        return $this->currentUser;
    }

}
