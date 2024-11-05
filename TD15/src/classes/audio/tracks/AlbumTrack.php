<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\tracks;
require_once 'vendor/autoload.php';


use iutnc\deefy\exception\InvalidPropertyNameException as InvalidPropertyNameException;
use iutnc\deefy\exception\NonEditablePropertyException as NonEditablePropertyException;
use iutnc\deefy\exception\InvalidPropertyNameException as InvalidPropertyValueException;

class AlbumTrack extends AudioTrack {
    private string $album;
    private int $numPiste; 


    public function __construct(string $nom, string $chemin){
        parent::__construct($nom,$chemin);
        $this->album = "rien";
        $this->numPiste = 0;
    }

    public function __get(String $arg):mixed{
        if(property_exists($this, $arg)) return $this->$arg;
        throw new InvalidPropertyNameException ("$arg: invalid property");
    } 

    public function __set(String $arg1, mixed $arg2):void{
        if($arg1==="titre"||$arg1==="nomFichier") throw new NonEditablePropertyException("On ne peux pas modifier : $arg1");
        if($arg1==="duree"&&(gettype($arg2)!="integer"||$arg2<0)) throw new InvalidPropertyValueException("$arg1 | $arg2 : valeur invalide");
        if(property_exists($this, $arg1)){$this->$arg1=$arg2;
        }else{
            throw new InvalidPropertyNameException ("$arg1: invalid property");
        }
    }
}