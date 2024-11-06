<?php

namespace iutnc\deefy\db;

use iutnc\deefy\audio\tracks\AudioTrack;

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
        return $data;
    }

}