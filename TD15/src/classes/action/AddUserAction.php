<?php

namespace iutnc\deefy\action;
class AddUserAction extends ConnexionAction
{

    public function __construct()
    {
        parent::__construct("S'inscrire", "Se connecter", "?action=sign-in", false);
    }


    function getConnection(): string
    {
        $res = "";

        $e = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $p1= $_POST['password'];
        $p2 = $_POST['passwd2'];
        if($p1 === $p2){
            $res = "<p>".\iutnc\deefy\auth\AuthnProvider::register($e, $p1)."</p>";
        }else{
            $res = '<p>Mot de passe 1 et 2 différents</p>
                <form method="post" action="?action=add-user">
                <input type="email" name="email" placeholder="email" autofocus>
                <input type="text" name="passwd1" placeholder="password 1">
                <input type="text" name="passwd2" placeholder="password 2">
                <div class="signInOrUp">
                    <input type="submit" name="connex" value="Connéxion">
                    <a href="?action=add-user">Se connecter</a>
                </div>
                </form>';
        }

        return $res;
    }
}