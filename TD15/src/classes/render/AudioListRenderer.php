<?php
declare(strict_types=1);
namespace iutnc\deefy\render;
require_once 'vendor/autoload.php';

use iutnc\deefy\audio\lists\AudioList as AudioList;
class AudioListRenderer implements Renderer{

    public AudioList $list;

    public function __construct(AudioList $liste){
        $this->list = $liste;
    }

    public function render(int $selector = 0):string{
        $res = '<h2>'.$this->list->__get("nom").'</h2>';
        foreach ($this->list->list as $value){
            $res = $res.$value->__toString();
        }
        $res = $res."<h4>durée totale : {$this->list->__get("dureeTotale")}</h4> <h4>nombre de pistes : {$this->list->__get("nbPiste")}</h4>";
        return $res;
    }
}
