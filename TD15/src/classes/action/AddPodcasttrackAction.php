<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\tracks\PodcastTrack as PodcastTrack;
use iutnc\deefy\db\PlaylistService;
use iutnc\deefy\db\TrackService;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\NonEditablePropertyException;
use iutnc\deefy\models\User;
use iutnc\deefy\render\AudioListRenderer as AudioListRenderer;

class  AddPodcasttrackAction extends Action
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws NonEditablePropertyException
     * @throws InvalidPropertyNameException
     * @throws \Exception
     */
    public function execute(): string
    {
        if ($this->http_method == "GET") {
            $res = <<<addT
                <form method="post" action="?action=add-podcasttrack" enctype="multipart/form-data">
                    <input type="text" name="nom" placeholder="nom" autofocus>
                    <input type="text" name="artiste" placeholder="artiste">
                    <input type="text" name="genre" placeholder="genre">
                    <input type="number" name="duree" placeholder="duree">
                    <input type="date" name="date" placeholder="date">
                    <input type="file" name="file" accept="audio/mpeg"/>                
                    <input type="submit" name="ajouter" value="Ajouter Track">
                </form>
                addT;
        } else {
            // récupère la playlist créer ou le derniere
            if (!isset($_SESSION['user']['playlist'])) {
                $playlist = PlaylistService::getPlaylists();
                $_SESSION['user']['playlist'] = serialize($playlist[count($playlist) - 1]);
            }
            else {
                $fd = serialize(User::getCurrentUser()->getPlaylists()[0]);

                $playlist = unserialize($fd);
            }

            if ($_FILES['file']['type'] !== 'audio/mpeg') {
                throw new InvalidPropertyNameException("Le fichier n'est pas un fichier audio");
            }

            // upload du fichier
            $upload_dir = 'audio/';

            // Nom du fichier temporaire
            $tmpName = $_FILES['file']['tmp_name'];
            if (($_FILES['file']['error'] === UPLOAD_ERR_OK)) {
                $dest = $upload_dir . $_FILES['file']['name'];
                move_uploaded_file($tmpName, $dest);
            }

            // Ajout de la piste (id non défini)
            $track = new PodcastTrack(1, filter_var($_POST['nom'], FILTER_UNSAFE_RAW), "audio/" . $_FILES['file']['name']);
            $track->__set("artiste", filter_var($_POST['artiste'], FILTER_UNSAFE_RAW));
            $track->__set("genre", filter_var($_POST['genre'], FILTER_UNSAFE_RAW));
            $track->__set("duree", intval(filter_var($_POST['duree'], FILTER_SANITIZE_NUMBER_INT)));
            $track->__set("annee", filter_var($_POST['date'], FILTER_UNSAFE_RAW));

            $trackWithId = TrackService::addTrack($track);

            // Ajout de la piste à la playlist
            PlaylistService::addTrack($trackWithId, $playlist);

            $renderer = new AudioListRenderer($playlist);
            $res = <<<addT
                {$renderer->render()}
                <a href="?action=add-podcasttrack" class = "boutton">Ajouter encore une piste</a>
                addT;
        }
        return $res;
    }
}