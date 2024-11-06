<?php

namespace iutnc\deefy\models;

use iutnc\deefy\audio\lists\Playlist as Playlist;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\db\PlaylistService;
use iutnc\deefy\exception\InvalidPropertyNameException;
use PDO;

class User
{
    private int $id;
    private string $email;
    private string $password;
    private int $role;

    private ?array $userPlaylists;

    public static function getCurrentUser(): ?User
    {
        if (isset($_SESSION['user']['id'])) {
            $t = $_SESSION['user'];
            return new User($t['id'], $t['email'], $t['passwd'], $t['role']);
        }
        return null;
    }

    public function __construct(int $id, string $e, string $p, int $r)
    {
        $this->id = $id;
        $this->email = $e;
        $this->password = $p;
        $this->role = $r;

        $_SESSION['user']['id'] = $id;
        $_SESSION['user']['email'] = $e;
        $_SESSION['user']['password'] = $p;
        $_SESSION['user']['role'] = $r;
    }

    public static function isConnected() : bool
    {
        return isset($_SESSION['user']['id']);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function addPlaylist(Playlist $playList): void
    {
        if (!isset($this->userPlaylists)) {
            $playlists = PlaylistService::getPlaylists();
            $this->userPlaylists = $playlists;
        }

        $this->userPlaylists[] = $playList;
        $_SESSION['user']['playlist'] = serialize($playList);
    }

    public function getPlaylists(): array
    {
        if (!isset($this->userPlaylists)) {
            $playlists = PlaylistService::getPlaylists();
            $this->userPlaylists = $playlists;
            $_SESSION['user']['playlists'] = serialize($playlists);
        }
        return $this->userPlaylists;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isAdmin(): bool
    {
        return $this->role == 100;
    }
}