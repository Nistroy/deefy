<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\lists;

require_once 'vendor/autoload.php';

use iutnc\deefy\audio\lists\AudioList as AudioList;

class PlayList extends AudioList
{
    public function __construct(string $nom, iterable $audios)
    {
        parent::__construct($nom, $audios);
    }

    public function getNom(): string
    {
        return $this->nom;
    }

}