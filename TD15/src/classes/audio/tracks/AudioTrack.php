<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;
require_once 'vendor/autoload.php';


use iutnc\deefy\exception\InvalidPropertyNameException as InvalidPropertyNameException;
use iutnc\deefy\exception\NonEditablePropertyException as NonEditablePropertyException;
use iutnc\deefy\exception\InvalidPropertyNameException as InvalidPropertyValueException;

class AudioTrack
{
    private int $id;
    protected string $titre;
    protected string $artiste;
    protected string $annee;
    protected string $genre;
    protected int $duree;
    protected string $nomFichier;

    public function __construct(int $id, string $nom, string $chemin)
    {
        $this->id = $id;
        $this->titre = $nom;
        $this->nomFichier = $chemin;
        $this->artiste = '';
        $this->genre = '';
        $this->duree = 0;
        $this->annee = '';
    }

    public function __toString(): string
    {
        return "<p>$this->titre $this->artiste<p><audio controls src=$this->nomFichier>";
    }

    /**
     * @throws InvalidPropertyValueException
     */
    public function __get(string $arg): mixed
    {
        if (!property_exists($this, $arg)) {
            throw new InvalidPropertyNameException ("$arg: invalid property");
        }

        return $this->$arg;
    }

    /**
     * @throws NonEditablePropertyException
     * @throws InvalidPropertyValueException
     */
    public function __set(string $arg1, mixed $arg2): void
    {
        if ($arg1 === "titre" || $arg1 === "nomFichier") {
            throw new NonEditablePropertyException("On ne peux pas modifier : $arg1");
        }

        if ($arg1 === "duree" && (gettype($arg2) != "integer" || $arg2 < 0)) {
            throw new InvalidPropertyValueException("$arg1 | $arg2 : valeur invalide");
        }

        if (!property_exists($this, $arg1)) {
            throw new InvalidPropertyNameException ("$arg1: invalid property");
        }

        $this->$arg1 = $arg2;
    }

}
