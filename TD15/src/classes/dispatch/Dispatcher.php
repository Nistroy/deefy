<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcasttrackAction;
use iutnc\deefy\action\connection\SigninAction;
use iutnc\deefy\action\connection\SignOutAction;
use iutnc\deefy\action\connection\SignUpAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\db\Auth;
use iutnc\deefy\models\User;

class Dispatcher
{
    private string $action;

    public function __construct()
    {
        $this->action = "";
        if (isset($_GET['action'])) $this->action = $_GET['action'];
    }


    public function run(): void
    {
        $res = "Bienvenue !";


        if (User::isConnected()) {
            switch ($this->action) {

                case 'add-playlist':
                    $res = (new AddPlaylistAction())->execute();
                    break;
                case 'add-podcasttrack':
                    $res = (new AddPodcasttrackAction())->execute();
                    break;
                case 'display-playlist':
                    $res = (new DisplayPlaylistAction())->execute();
                    break;
                case 'sign-out':
                    $res = (new SignOutAction())->execute();
                    break;
                case 'sign-up':
                    $res = (new SignUpAction())->execute();
                    break;
                case 'sign-in':
                    $res = (new SigninAction())->execute();
                    break;
            }
        } else {
            $res = (new SigninAction())->execute();
        }
        $this->renderPage($res);
    }

    private function renderPage(string $html): void
    {
        echo <<<end
            <!DOCTYPE html>
            <html lang="fr" dir="ltr" xmlns="http://www.w3.org/1999/html">
            <head>
                <meta charset="utf-8">
                <meta name=”viewport” content="initial-scale=1.0">
                <link rel="stylesheet" href="./main.css">
                <title>Index</title>
            </head>
            <body>
            end;
        if (!User::isConnected()) {
            echo <<<end
                <header>
                    <nav>
                    <h1>Deefy</h1>
                </nav>
                </header>
                end;


        } else {
            echo <<<end
                <header>
                    <nav>
                        <h1>Deefy</h1>
                        <ul>
                            <li><a href="?" class ="boutton">Accueil</a></li>
                            <li><a href="?action=add-playlist" class ="boutton">Créer Playliste</a></li>
                            <li><a href="?action=add-podcasttrack" class ="boutton">Ajouter une piste à la playlist</a></li>
                            <li><a href="?action=display-playlist" class ="boutton">Afficher Playlist</a></li>
                            <div style="flex-grow: 1;"></div>
                            <li><a href="?action=sign-in" class ="boutton"><img src="images/user.png" alt="user" width="20px" height="20px"></a></li>
                            
                        </ul>
                        </nav>
                    </header>
                end;
        }
        echo <<<end
                    <div class="wrapper">
                        $html
                    </div>
            </body>
            </html>
            end;
    }
}