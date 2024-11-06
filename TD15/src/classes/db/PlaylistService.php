<?php

namespace iutnc\deefy\db;

use Exception;
use iutnc\deefy\audio\lists\PlayList as Playlist;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\models\User;
use PDO;

abstract class PlaylistService
{
    /**
     * @throws InvalidPropertyNameException
     */
    public static function getPlaylists(): array
    {
        $bd = ConnectionFactory::makeConnection();

        $query = "SELECT p.nom as nom, p.id as id from user u inner join user2playlist u2 on u.id = u2.id_user
                                        inner join playlist p on u2.id_pl = p.id
                            where u.email like ?";
        $prep = $bd->prepare($query);
        $email = User::getCurrentUser()->getEmail();
        $prep->bindParam(1, $email);
        $prep->execute();

        $tab = [];
        while ($data = $prep->fetch(PDO::FETCH_ASSOC)) {
            $playlist = new Playlist($data['id'], $data['nom'], []);
            $playlist->setTracks(self::getTracks($playlist));
            $tab[] = $playlist;
        }

        return $tab;
    }

    /**
     * @throws Exception
     */
    public static function addTrack(AudioTrack $track, Playlist $playlist): void
    {
        // récupérer l'id du track
        $bd = ConnectionFactory::makeConnection();

        $idTrack = $track->__get('id');

        // récupérer l'id de la playlist
        $idPlaylist = $playlist->__get('id');

        // Ajout du track à la playlist (en base de données)
        $query = "INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste) VALUES (?, ?, ?)";
        $prep = $bd->prepare($query);
        $prep->bindParam(1, $idPlaylist);
        $prep->bindParam(2, $idTrack);

        $list = $playlist->list;
        $count = count($list);
        $noPisteListe = $count + 1;

        $prep->bindParam(3, $noPisteListe);

        $prep->execute();

        // Ajout du track à la playlist (en mémoire)
        $playlists = User::getCurrentUser()->getPlaylists();
        foreach ($playlists as $p) {
            if ($p->__get('id') == $playlist->__get('id')) {
                $p->addTrack($track);
            }
        }
    }

    /**
     * @throws InvalidPropertyNameException
     */
    private static function getTracks(Playlist $playlist): array
    {
        $bd = ConnectionFactory::makeConnection();

        $query = "SELECT t.id as id, t.titre as titre, t.filename as nomFichier, t.artiste_album as artiste_album, t.genre as genre, t.duree as duree, t.annee_album as annee_album
                    from track t inner join playlist2track t2p on t.id = t2p.id_track
                            where t2p.id_pl = ?";
        $prep = $bd->prepare($query);
        $idPlaylist = $playlist->__get('id');
        $prep->bindParam(1, $idPlaylist);
        $prep->execute();

        $tab = [];
        while ($data = $prep->fetch(PDO::FETCH_ASSOC)) {
            $track = TrackService::dataToTrack($data);
            $tab[] = $track;
        }

        return $tab;
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public static function getPlaylistFromUser(int $intval, User $user): Playlist
    {
        $bd = ConnectionFactory::makeConnection();
        $query = "SELECT p.nom as nom, p.id as id from user u inner join user2playlist u2 on u.id = u2.id_user
                                        inner join playlist p on u2.id_pl = p.id
                            where u.email like ? and p.id = ?";

        $prep = $bd->prepare($query);
        $email = $user->getEmail();
        $prep->bindParam(1, $email);
        $prep->bindParam(2, $intval);
        $prep->execute();

        $data = $prep->fetch(PDO::FETCH_ASSOC);
        $playlist = new Playlist($data['id'], $data['nom'], []);
        $playlist->setTracks(self::getTracks($playlist));

        return $playlist;
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public static function addPlaylist(Playlist $playlist): void
    {
        $bd = ConnectionFactory::makeConnection();

        $query = "INSERT INTO playlist (nom) VALUES (?)";
        $prep = $bd->prepare($query);
        $prep->bindParam(1, $playlist->nom);
        $prep->execute();

        $query = "SELECT id from playlist where nom like ?";
        $prep = $bd->prepare($query);
        $prep->bindParam(1, $playlist->nom);
        $prep->execute();

        $data = $prep->fetch(PDO::FETCH_ASSOC);
        $playlist->id = $data['id'];

        $query = "INSERT INTO user2playlist (id_user, id_pl) VALUES (?, ?)";
        $prep = $bd->prepare($query);
        $id = User::getCurrentUser()->getId();
        $prep->bindParam(1, $id);
        $prep->bindParam(2, $playlist->id);
        $prep->execute();

        User::getCurrentUser()->addPlaylist($playlist);
    }


    /**
     * @throws InvalidPropertyNameException
     */
    public static function getPlaylistsWithName(string $nomPlaylist): array
    {
        $bd = ConnectionFactory::makeConnection();
        $query = "SELECT p.nom as nom, p.id as id from user u inner join user2playlist u2 on u.id = u2.id_user
                                        inner join playlist p on u2.id_pl = p.id
                            where u.email like ? and p.nom like ?";

        $prep = $bd->prepare($query);
        $email = User::getCurrentUser()->getEmail();
        $prep->bindParam(1, $email);

        $nomPlaylist = '%' . $nomPlaylist . '%';
        $prep->bindParam(2, $nomPlaylist);
        $prep->execute();

        $data = $prep->fetchAll(PDO::FETCH_ASSOC);
        $tab = [];
        foreach ($data as $d) {
            $playlist = new Playlist($d['id'], $d['nom'], []);
            $playlist->setTracks(self::getTracks($playlist));
            $tab[] = $playlist;
        }

        return $tab;
    }

    /**
     * Méthode pour l'admin
     * @param mixed $idPlaylist
     * @return Playlist
     * @throws InvalidPropertyNameException
     */
    public static function getAllPlaylistWithId(mixed $idPlaylist)
    {
        $bd = ConnectionFactory::makeConnection();
        $query = "SELECT p.nom as nom, p.id as id from playlist p
                            where p.id = ?";

        $prep = $bd->prepare($query);
        $prep->bindParam(1, $idPlaylist);
        $prep->execute();

        $data = $prep->fetch(PDO::FETCH_ASSOC);
        $playlist = new Playlist($data['id'], $data['nom'], []);
        $playlist->setTracks(self::getTracks($playlist));

        return $playlist;
    }
}