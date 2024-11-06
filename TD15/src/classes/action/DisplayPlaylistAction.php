<?php

namespace iutnc\deefy\action;

use Exception;
use iutnc\deefy\audio\lists\PlayList;
use iutnc\deefy\db\Auth;
use iutnc\deefy\db\PlaylistService;
use iutnc\deefy\models\User;
use iutnc\deefy\render\AudioListRenderer;

class DisplayPlaylistAction extends Action
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function execute(): string
    {
        $res = '';

        if (User::getCurrentUser()->isAdmin()) {
            $res .= '<h2>Playlistes de tous les utilisateurs</h2>';
        } else {
            $res .= '<h2>Playlistes de l\'utilisateur ' . User::getCurrentUser()->getEmail() . '</h2>';
        }

        // check si dans le get il y a un idPlaylist
        if (isset($_GET['idPlaylist'])) {
            $idPlaylist = $_GET['idPlaylist'];

            if (User::getCurrentUser()->isAdmin()) {
                $playlist = PlaylistService::getPlaylistWithId($idPlaylist);
            } else {
                $playlist = PlaylistService::getPlaylistFromUser($idPlaylist, User::getCurrentUser());
            }
            $res .= (new AudioListRenderer($playlist))->render();

            return $res;
        }

        $nomPlaylist = $_POST['nomPlaylist'] ?? '';
        $res .= "<form method=\"post\" action=\"?action=display-playlist\">
                    <input type=\"text\" name=\"nomPlaylist\" placeholder=\"nom\" value=\"$nomPlaylist\" autofocus>
                    <input type=\"submit\" name=\"chercher\" value=\"Chercher\">
                    </form>";

        if ($this->http_method == "POST" && isset($_POST['nomPlaylist']) && $_POST['nomPlaylist'] != '') {
            $nomPlaylist = $_POST['nomPlaylist'];
            $playlists = PlaylistService::getPlaylistsWithName($nomPlaylist);
        }
        else {
            $playlists = PlaylistService::getPlaylists();
        }

        foreach ($playlists as $playlist) {
            $res .= "<a href=\"?action=display-playlist&idPlaylist=$playlist->id\">$playlist->nom</a><br>";
        }

        if ($playlists == []) {
            $res .= "Aucune playlist trouvée";
        }

        return $res;
    }
}