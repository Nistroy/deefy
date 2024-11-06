<?php

namespace iutnc\deefy\action;

class SignOutAction extends Action
{

    public function execute(): string
    {
        session_destroy();
        unset($_SESSION['user']);
        return "Vous êtes déconnecté";
    }
}