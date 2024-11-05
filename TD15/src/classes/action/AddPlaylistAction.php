<?php
namespace iutnc\deefy\action;
use iutnc\deefy\audio\lists\Playlist as Playlist;
use iutnc\deefy\render\AudioListRenderer as AudioListRenderer;
class AddPlaylistAction extends Action {
    
    public function __construct(){
        parent::__construct();
    }

    public function execute() : string{
        $res = "";
        if($this->http_method == "GET"){    
            $res = <<<addP
                <form method="post" action="?action=add-playlist">
                <input type="text" name="nom" placeholder="nom" autofocus>
                <input type="submit" name="creer" value="Créer PlayListe">
                </form>
                addP;
        }else{
            $l = new Playlist(filter_var($_POST['nom'], FILTER_SANITIZE_STRING), []);
            $_SESSION['user']['playlist'] = serialize($l);
            $r = new AudioListRenderer($l);
            $res= <<<addP
                {$r->render()}
                <a href="?action=add-podcasttrack">Ajouter une piste</a>
                addP;
        }
        return $res;
    }
}