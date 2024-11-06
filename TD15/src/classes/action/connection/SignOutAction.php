<?php

namespace iutnc\deefy\action\connection;

use iutnc\deefy\action\Action;

class SignOutAction extends Action
{
    public function execute(): string
    {
        session_destroy();
        unset($_SESSION['user']);

        return "<p>Vous êtes déconnecté</p>"
            . "<form method='post' action='?action=sign-in'>"
            . "<input type='submit' value='Se connecter'>"
            . "</form>";
    }
}