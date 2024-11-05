<?php
declare(strict_types=1);
namespace iutnc\deefy\render;
require_once 'vendor/autoload.php';
use iutnc\deefy\audio\tracks\PodcastTrack as PodcastTrack;
use iutnc\deefy\audio\tracks\AudioTrack as AudioTrack;
class PodcastTrackRenderer extends \AudioTrackRenderer{

    public AudioTrack $piste;

    public function __construct(PodcastTrack $piste){
        $this->piste = $piste;
    }

    public function long():string{
        return  "<p>titre : {$this->piste->titre}</p> <p>genre : {$this->piste->genre}</p> <p>duree : {$this->piste->duree}</p>  <p>annee : {$this->piste->annee}</p> <p>emplacement fichier : {$this->piste->nomFichier}</p>";
    }

    public function compact():string{
        return "<p>{$this->piste->__toString()}</p>";
    } 
}
