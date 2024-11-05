<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\lists;

require_once 'vendor/autoload.php';

use iutnc\deefy\exception\InvalidPropertyNameException as InvalidPropertyNameException;
class AudioList{
    protected String $nom;
    protected int $nbPiste;
    protected int $dureeTotale;
    protected iterable $list;

    public function __construct(String $nom, iterable $tab = []){
        $this->nom=$nom;
        $this->list=$tab;
        $this->dureeTotale = 0;
        $this->nbPiste = 0;
        foreach ($tab as $value) {
            $this->nbPiste ++;
            $this->dureeTotale+=$value->duree;
        }
    }

    public function __get(String $arg):mixed{
        if(property_exists($this, $arg)) return $this->$arg;
        throw new InvalidPropertyNameException ("$arg: invalid property");
    } 
}