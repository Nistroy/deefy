<?php

namespace iutnc\deefy\db;

use iutnc\deefy\audio\lists\PlayList as Playlist;
use iutnc\deefy\models\User;
use PDO;

class PlaylistService
{
    public static function getPlaylists(): array
    {
        $bd = ConnectionFactory::makeConnection();

        $query ="SELECT p.nom as nom, p.id as idp from user u inner join user2playlist u2 on u.id = u2.id_user
                                        inner join playlist p on u2.id_pl = p.id
                            where u.email like ?";
        $prep = $bd->prepare($query);
        $email = User::getCurrentUser()->getEmail();
        $prep->bindParam(1, $email);
        $prep->execute();

        $tab=[];
        while($data=$prep->fetch(PDO::FETCH_ASSOC)){
            $tab[$data['idp']] = new Playlist($data['nom'], []);
        }

        return $tab;
    }
}