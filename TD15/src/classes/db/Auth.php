<?php

namespace iutnc\deefy\db;

use Exception;
use iutnc\deefy\exception\AuthException as AuthException;
use iutnc\deefy\models\User;
use PDO;

class Auth
{

    /**
     * @throws AuthException
     */
    public static function signIn(string $e, string $p): bool
    {
        $bd = ConnectionFactory::makeConnection();
        $query = "select * from User where email = ? ";
        $prep = $bd->prepare($query);
        $prep->bindParam(1, $e);
        $canConnect = $prep->execute();

        if (!$canConnect) {
            throw new AuthException("Erreur de connexion");
        }

        $userData = $prep->fetchall(PDO::FETCH_ASSOC);

        if (sizeof($userData) == 0) {
            throw new AuthException("Utilisateur inconnu");
        }

        if (!password_verify($p, $userData[0]['passwd'])) {
            throw new AuthException("Mot de passe incorrect");
        }

        $_SESSION['user'] = $userData[0];

        return true;
    }


    public static function register(string $e, string $p): string
    {
        $minimumLength = 10;

        //verification compte
        $bd = ConnectionFactory::makeConnection();
        $query = "select passwd from user where email = ? ";
        $prep = $bd->prepare($query);
        $prep->bindParam(1, $e);
        $prep->execute();
        $userData = $prep->fetchall(PDO::FETCH_ASSOC);

        if ((strlen($p) < $minimumLength)) {
            return "La taille du mot de passe est trop court";
        }

        if (!sizeof($userData) == 0) {
            return "Utilisateur déjà existant";
        }

        //hash the password
        $hash = password_hash($p, PASSWORD_DEFAULT, ['cost' => 10]);

        try {
            UserService::createUser($e, $hash);
            $res = "Inscription réussie";
        } catch (Exception $e) {
            $res = "Echec inscription";
        }


        return $res;
    }

    public static function checkAccess(int $id): bool
    {
        $res = false;

        $bd = ConnectionFactory::makeConnection();
        $query = "SELECT u.email as email from user u inner join user2playlist p on u.id = p.id_user where id_pl = ? ";
        $prep = $bd->prepare($query);
        $prep->bindParam(1, $id);
        $canAcessPlaylist = $prep->execute();
        $playlistsData = $prep->fetchall(PDO::FETCH_ASSOC);
        if ($canAcessPlaylist && sizeof($playlistsData) > 0) {
            if ($playlistsData[0]['email'] === User::getCurrentUser()->getEmail() || User::getCurrentUser()->isAdmin()) {
                $res = true;
            }
        }
        return $res;
    }

    public static function isConnected(): bool
    {
        return isset($_SESSION['user']['id']);
    }

}
