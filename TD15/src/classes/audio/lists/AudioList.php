<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\lists;

require_once 'vendor/autoload.php';

use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\exception\InvalidPropertyNameException as InvalidPropertyNameException;

class AudioList
{
    protected int $id;
    protected string $nom;
    protected int $nbPiste;
    protected int $dureeTotale;
    protected iterable $list;

    public function __construct(int $id,  string $nom, iterable $audios = [])
    {
        $this->id = $id;
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
    public function &__get(string $arg): mixed
    {
        if (!property_exists($this, $arg)) {
            throw new InvalidPropertyNameException("$arg: invalid property");
        }

        return $this->$arg;
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function __set(string $arg1, mixed $arg2): void
    {
        if (!property_exists($this, $arg1)) {
            throw new InvalidPropertyNameException("$arg1: invalid property");
        }

        $this->$arg1 = $arg2;
    }
}