<?php

namespace iutnc\deefy\db;

use Exception;
use iutnc\deefy\models\User;

abstract class UserService
{
    /**
     * @throws Exception
     */
    public static function createUser(string $email, string $password): void
    {
        $bd = ConnectionFactory::makeConnection();

        $query = "INSERT INTO user (email, passwd, role) VALUES (?, ?, ?)";
        $prep = $bd->prepare($query);
        $prep->bindParam(1, $email);
        $prep->bindParam(2, $password);

        // role 1 parce que c'est un utilisateur normal
        $role = 1;
        $prep->bindParam(3, $role);
        $registerSuccess = $prep->execute();
        if (!$registerSuccess) {
            throw new Exception("Failed to register user");
        }

        // pour récupérer l'id de l'utilisateur
        $query = "SELECT * FROM user WHERE email = ?";
        $prep = $bd->prepare($query);
        $prep->bindParam(1, $email);
        $prep->execute();
        $data = $prep->fetch();
        $user = new User($data['id'], $data['email'], $data['passwd'], $data['role']);
        $_SESSION['user'] = $user;
    }
}