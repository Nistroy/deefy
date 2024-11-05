<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\lists;

require_once 'vendor/autoload.php';

use iutnc\deefy\exception\InvalidPropertyNameException as InvalidPropertyNameException;
use iutnc\deefy\exception\NonEditablePropertyException as NonEditablePropertyException;
class AlbumList extends AudioList{
    private string $artiste;
    private int $dateSortie;

    public function __construct(String $nom, iterable $tab, String $artiste, int $date){
        parent::__construct($nom, $tab);
        $this->artiste = $artiste;
        $this->dateSortie = $date;
    }

    public function __set(String $arg1, mixed $arg2):void{
        if($arg1==="nom"||$arg1==="nbPiste"||$arg1==="dureeTotale"||$arg1==="list") throw new NonEditablePropertyException("On ne peux pas modifier : $arg1");;
        if(property_exists($this, $arg1)){$this->$arg1=$arg2;
        }else{throw new InvalidPropertyNameException ("$arg1: invalid property");}
    }

}