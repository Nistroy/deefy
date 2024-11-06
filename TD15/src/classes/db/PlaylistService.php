<?php

namespace iutnc\deefy\db;

use Exception;
use iutnc\deefy\audio\lists\PlayList as Playlist;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\models\User;
use PDO;

abstract class PlaylistService
{
    public static function getPlaylists(): array
    {
        $bd = ConnectionFactory::makeConnection();

        $query = "SELECT p.nom as nom, p.id as idp from user u inner join user2playlist u2 on u.id = u2.id_user
                                        inner join playlist p on u2.id_pl = p.id
                            where u.email like ?";
        $prep = $bd->prepare($query);
        $email = User::getCurrentUser()->getEmail();
        $prep->bindParam(1, $email);
        $prep->execute();

        $tab = [];
        while ($data = $prep->fetch(PDO::FETCH_ASSOC)) {
            $tab[$data['idp']] = new Playlist($data['nom'], []);
        }

        return $tab;
    }


    /**
     * @throws Exception
     */
    public static function addTrack(AudioTrack $track, Playlist $playlist): void
    {
        $playlists = User::getCurrentUser()->getPlaylists();
        $playlistFinded = array_search($playlist, $playlists);
        if ($playlistFinded === false) {
            throw new Exception("Playlist not found");
        }
        else {
            User::getCurrentUser()->getPlaylists()[$playlistFinded]->addTrack($track);
        }

        $bd = ConnectionFactory::makeConnection();
        $getTrackQuery = "SELECT id from track where titre like ?";
        $prep = $bd->prepare($getTrackQuery);
        $prep->bindParam(1, $track->getTitre());
        $prep->execute();

        $idTrack = $prep->fetch(PDO::FETCH_ASSOC)['id'];

        $getPlaylistQuery = "SELECT id from playlist where nom like ?";
        $prep = $bd->prepare($getPlaylistQuery);
        $nom = $playlist->getNom();
        $prep->bindParam(1, $nom);
        $prep->execute();


    }
}