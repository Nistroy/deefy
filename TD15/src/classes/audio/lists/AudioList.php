<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\lists;

require_once 'vendor/autoload.php';

use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\exception\InvalidPropertyNameException as InvalidPropertyNameException;

class AudioList
{
    protected string $nom;
    protected int $nbPiste;
    protected int $dureeTotale;
    protected iterable $list;

    public function __construct(string $nom, iterable $audios = [])
    {
        $this->nom = $nom;
        $this->list = $audios;
        $this->dureeTotale = 0;
        $this->nbPiste = 0;

        foreach ($audios as $audio) {
            $this->nbPiste++;
            $this->dureeTotale += $audio->duree;
        }
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function __get(string $arg): mixed
    {
        if (!property_exists($this, $arg)) {
            throw new InvalidPropertyNameException("$arg: invalid property");
        }

        return $this->$arg;
    }

    public function addAudio(AudioTrack $audio): void
    {
        $this->list[] = $audio;
        $this->nbPiste++;
        $this->dureeTotale += $audio->duree;
    }

    public function deleteAudio(AudioTrack $audio): void
    {
        $key = array_search($audio, $this->list);
        if ($key !== false) {
            unset($this->list[$key]);
            $this->nbPiste--;
            $this->dureeTotale -= $audio->duree;
        }
    }
}