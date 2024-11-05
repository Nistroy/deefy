<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\user\User;
use PDO;
use iutnc\deefy\render\AudioListRenderer as AudioListRenderer;

class SigninAction extends Action
{

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $res = "";
        if ($this->http_method == "GET") {
            $res = '<form method="post" action="?action=sign-in">
                <input type="email" name="email" placeholder="email" autofocus>
                <input type="text" name="password" placeholder="mot de passe">
                <div class="signInOrUp">
                <input type="submit" name="connex" value="Connexion">
                    <a href="?action=add-user">S\'inscrire</a>
                </div>
                </form>';
        } else {
            $e = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $p = $_POST['password'];
            $bool = false;

            // on essaie de se connecter
            try {
                $bool = AuthnProvider::authenticate($e, $p);
            } catch (AuthException $e) {
                $res = "<p>Identifiant et/ou mot de passe invalide</p>";
            }

            if ($bool) {
                // on récupère les playlists de l'utilisateur
                $u = new User($e, $p, 1);
                $t = $u->getPlaylists();
                $res = <<<start
                    <h3>Connexion réussite pour $e</h3>
                    <h3>Playlists de l'utilisateur : </h3>
                start;


                $bd = ConnectionFactory::makeConnection();

                // on affiche les playlists de l'utilisateur
                // on récupère l'id de la playlist pour pouvoir afficher les détails
                foreach ($t as $k => $value) {
                    $nom = $value->__get("nom");
                    $query = "SELECT id from playlist p where p.nom like ?";
                    $playlists = $bd->prepare($query);
                    $playlists->bindParam(1, $nom);
                    $playlists->execute();

                    while ($play = $playlists->fetch(PDO::FETCH_ASSOC)) {
                        $res .= '<a href="?action=display-playlist&id=' . $play['id'] . '"> - ' . $nom . '</a>';
                    }
                }
            }
        }
        return $res;
    }
}