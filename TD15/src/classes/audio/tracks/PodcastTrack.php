<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\tracks;
require_once 'vendor/autoload.php';
class PodcastTrack extends AudioTrack{
    public function __construct(int $id, string $titre, string $nomFichier){
        parent::__construct($id, $titre, $nomFichier);
    }

    // affiche un warning si je ne fais pas ça
    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getArtiste(): string
    {
        return $this->artiste;
    }

    public function getAnnee(): string
    {
        return $this->annee;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    public function getNomFichier(): string
    {
        return $this->nomFichier;
    }
}