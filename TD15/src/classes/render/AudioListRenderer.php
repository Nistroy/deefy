<?php
declare(strict_types=1);

namespace iutnc\deefy\render;
require_once 'vendor/autoload.php';

use iutnc\deefy\audio\lists\AudioList as AudioList;
use iutnc\deefy\exception\InvalidPropertyNameException;

class AudioListRenderer implements Renderer
{
    public AudioList $list;

    public function __construct(AudioList $list)
    {
        $this->list = $list;
    }

    /**
     * @param int $selector Affichage en COMPACT ou en LONG
     * @return string
     * @throws InvalidPropertyNameException
     */
    public function render(int $selector = 0): string
    {
        $nbpiste = 0;
        $dureeTotale = 0;
        foreach ($this->list->list as $audio) {
            $dureeTotale += $audio->duree;
            ++$nbpiste;
        }

        $res = '<h2>' . "Nom de la Playlist : " . $this->list->__get("nom") . '</h2>';
        foreach ($this->list->list as $value) {
            $res .= $value->__toString();
        }

        return $res .
            "<h4>Durée totale : {$dureeTotale}</h4>
            <h4>Nombre de pistes : {$nbpiste}</h4>";
    }
}
