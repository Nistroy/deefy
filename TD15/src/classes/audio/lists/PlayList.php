<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\lists;

require_once 'vendor/autoload.php';

use iutnc\deefy\audio\lists\AudioList as AudioList;
use iutnc\deefy\audio\tracks\AudioTrack;

class PlayList extends AudioList
{
    public function __construct(int $id,  string $nom, array $tracks)
    {
        parent::__construct($id, $nom, $tracks);
    }

    public function setTracks(array $getTracks): void
    {
        $this->list = $getTracks;
    }

    public function addTrack(AudioTrack $track): void
    {
        $this->list[] = $track;
    }
}