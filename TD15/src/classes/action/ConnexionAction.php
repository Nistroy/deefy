<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\connection\SignOutAction;
use iutnc\deefy\models\User;

abstract class ConnexionAction extends Action
{
    public function execute(): string
    {
        $res = "";
        if (isset($_POST['connex'])) {
            $res = '<br>' . $this->getConnection();
        }

        if (User::isConnected()) {
            return
            "<form method='post' action='?action=sign-out'>
                <p>Vous êtes déjà connecté</p>
                <input type='submit' value='Déconnexion'>
            </form>";
        }

        return $res . "<form method='post'>" .
            $this->buildEmailTextField() .
            $this->buildPasswordTextField() .
            $this->buildSignInOrUp();

    }

    protected function buildEmailTextField(): string
    {
        return '<input type="email" name="email" placeholder="email" autofocus>';
    }

    protected function buildPasswordTextField(): string
    {
        $res = "<input type='password' name='password' placeholder='Mot de passe'>";
        if (isset($_GET['action']) && $_GET['action'] == 'sign-up') {
            $res .= '<input type = "password" name = "passwd2" placeholder = "Confirmer mot de passe" > ';
        }

        return $res;
    }

    protected function buildSignInOrUp(): string
    {
        $submitButtonText = 'Connexion';
        $secondButtonText = 'Inscription';
        $secondButtonAction = 'sign-up';
        if (isset($_GET['action'])) {
            $submitButtonText = $_GET['action'] == 'sign-up' ? 'Inscription' : 'Connexion';
            $secondButtonText = $_GET['action'] == 'sign-up' ? 'Se connecter' : 'Inscription';

            $secondButtonAction = $_GET['action'] == 'sign-up' ? 'sign-in' : 'sign-up';
        }


        return '<div class="signInOrUp">
                    <input type="submit" name="connex" value="' . $submitButtonText . '">
                    <a class="btn-link" href="?action=' . $secondButtonAction . '">' . $secondButtonText . ' </a>
                </div>';
    }

    protected abstract function getConnection(): string;
}