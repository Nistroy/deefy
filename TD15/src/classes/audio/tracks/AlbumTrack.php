<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;
require_once 'vendor/autoload.php';


use iutnc\deefy\exception\InvalidPropertyNameException as InvalidPropertyNameException;
use iutnc\deefy\exception\NonEditablePropertyException as NonEditablePropertyException;
use iutnc\deefy\exception\InvalidPropertyNameException as InvalidPropertyValueException;

class AlbumTrack extends AudioTrack
{
    private string $album;
    private int $numPiste;


    public function __construct(string $nom, string $chemin)
    {
        parent::__construct($nom, $chemin);
        $this->album = "rien";
        $this->numPiste = 0;
    }

    public function __get(string $arg): mixed
    {
        if ($arg === "album" || $arg === "numPiste") {
            return $this->$arg;
        }

        return parent::__get($arg);
    }

    public function __set(string $arg1, mixed $arg2): void
    {
        parent::__set($arg1, $arg2);

        if ($arg1 === "duree" && (gettype($arg2) != "integer" || $arg2 < 0)) {
            throw new InvalidPropertyValueException("$arg1 | $arg2 : valeur invalide");
        }

        if (!property_exists($this, $arg1)) {
            throw new InvalidPropertyNameException ("$arg1: invalid property");
        }

        $this->$arg1 = $arg2;

    }
}