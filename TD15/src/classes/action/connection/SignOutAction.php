<?php

namespace iutnc\deefy\action\connection;

use iutnc\deefy\action\Action;

class SignOutAction extends Action
{

    public function execute(): string
    {
        session_destroy();
        unset($_SESSION['user']);
        return "Vous êtes déconnecté";
    }
}