<?php

namespace iutnc\deefy\db;

use Exception;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;

abstract class TrackService
{
    public static function getTrack(string $titre): AudioTrack
    {
        $bd = ConnectionFactory::makeConnection();

        $query = "SELECT * from track where titre like ?";
        $prep = $bd->prepare($query);
        $prep->bindParam(1, $titre);
        $prep->execute();

        $data = $prep->fetch();

        return self::dataToTrack($data);
    }

    public static function dataToTrack(array $data): AudioTrack
    {
        $track = new AudioTrack($data['id'], $data['titre'], $data['nomFichier']);

        // faire des try catchs pour les propriétés modifiables
        try {
            if (isset($data['artiste_album'])) {
                $track->artiste = $data['artiste_album'];
            }
        } catch (Exception $e) {
            // ne rien faire
        }

        try {
            if (isset($data['genre'])) {
                $track->genre = $data['genre'];
            }
        } catch (Exception $e) {
            // ne rien faire
        }

        try {
            if (isset($data['duree'])) {
                $track->duree = $data['duree'];
            }
        } catch (Exception $e) {
            // ne rien faire
        }

        try {
            if (isset($data['annee_album'])) {
                $track->annee = strval($data['annee_album']);
            }
        } catch (Exception $e) {
            // ne rien faire
        }

        return $track;
    }

    public static function addTrack(PodcastTrack $track) : PodcastTrack
    {

        $bd = ConnectionFactory::makeConnection();

        $query = "INSERT INTO track (titre, filename, artiste_album, genre, duree, annee_album) VALUES (?, ?, ?, ?, ?, ?)";
        $prep = $bd->prepare($query);
        $titre = $track->getTitre();
        $prep->bindParam(1, $titre);
        $nomFichier = $track->getNomFichier();
        $prep->bindParam(2, $nomFichier);
        $artiste = $track->getArtiste();
        $prep->bindParam(3, $artiste);
        $genre = $track->getGenre();
        $prep->bindParam(4, $genre);
        $duree = $track->getDuree();
        $prep->bindParam(5, $duree);
        $annee = $track->getAnnee();
        $prep->bindParam(6, $annee);
        $prep->execute();

        $track->id = intval($bd->lastInsertId());
        return $track;
    }
}