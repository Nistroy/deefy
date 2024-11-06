<?php
namespace iutnc\deefy\action;
use Exception;
use iutnc\deefy\audio\lists\PlayList;
use iutnc\deefy\db\Auth;
use iutnc\deefy\models\User;
use iutnc\deefy\render\AudioListRenderer;

class DisplayPlaylistAction extends Action {
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function execute() : string{
        $playlist = User::getCurrentUser()->getPlaylists()[0];
        $renderer = new AudioListRenderer($playlist);
        return $renderer->render();


        if(isset($_GET['id'])){
            if(Auth::checkAccess(intval($_GET['id']))){
                $p = PlayList::find(intval($_GET['id']));
                $r  = new AudioListRenderer($p);
                $res = $r->render();
            }else{
                try{
                    $p = PlayList::find(intval($_GET['id']));
                    $res = "Accès refusé : forbidden";   
                }catch(Exception $e){
                    $res = "Playliste avec id {$_GET['id']} n'éxiste pas";
                }
            }
        }else{
            if($this->http_method== "GET"){
                $res='<form method="post" action="?action=display-playlist">
                    <input type="number" name="id" placeholder="id" autofocus>
                    <input type="submit" name="connex" value="Chercher">
                    </form>';
            }else{
                $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
                $res=<<<aff
                <a href="?action=display-playlist&id=$id">-> Afficher PlayListe</a>
                aff;
            }
        }
        return $res;
    }
}