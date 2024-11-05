<?php
declare(strict_types=1);
namespace iutnc\deefy\render;

require_once 'vendor/autoload.php';

use iutnc\deefy\audio\tracks\AlbumTrack as AlbumTrack;
class AlbumTrackRenderer extends AudioTrackRenderer{

    public function __construct(){
        parent::__construct();
     }

    public function long():string{
        return "<p>titre : {$this->piste->titre}</p> <p>album : {$this->piste->album}</p> <p>genre : {$this->piste->genre}</p> <p>duree : {$this->piste->duree}</p> <p>numero : {$this->piste->numPiste}</p>  <p>annee : {$this->piste->annee}</p> <p>emplacement fichier : {$this->piste->nomFichier}</p>"; 
    }

    public function compact():string{
        return "<p>{$this->piste->__toString()}</p>";
    }   
}
