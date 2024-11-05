<?php
declare(strict_types=1);
namespace iutnc\deefy\render;
require_once 'vendor/autoload.php';
use iutnc\deefy\audio\tracks\AudioTrack as AudioTrack;
abstract class AudioTrackRenderer implements Renderer{

    protected AudioTrack $piste;

    public function __construct(AudioTrack $piste){
        $this->piste = $piste;
    }

    public function render(int $selector): string{
        $res = "";
        switch($selector){
            case (Renderer::COMPACT):
                $res = $this->compact();
                break;
            case (Renderer::LONG):
                $res = $this->long();
                break;
        }
        return $res;
    }


    public abstract function long():string;

    public abstract function compact():string;
}
