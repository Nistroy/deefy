<?php

namespace iutnc\deefy\action\connection;

use iutnc\deefy\action\ConnexionAction;
use iutnc\deefy\db\Auth;
use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\models\User;
use PDO;

class SigninAction extends ConnexionAction
{

    public function __construct()
    {
        parent::__construct("Se connecter", "S'inscrire", "?action=sign-up", true);
    }

    function getConnection(): string
    {
        $e = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $p = $_POST['password'];
        $connectionSuccess = false;

        // on essaie de se connecter
        try {
            $connectionSuccess = Auth::signIn($e, $p);
        } catch (AuthException $e) {
            return "<p>Identifiant et/ou mot de passe invalide</p>";
        }

        // si la connexion a réussi
        if ($connectionSuccess) {
            $res = "<p>Connexion réussie</p>";
        }
        else {
            $res = "<p>Connexion échouée</p>";
        }

        return $res;
    }
}