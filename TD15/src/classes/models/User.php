<?php

namespace iutnc\deefy\models;

use iutnc\deefy\audio\lists\Playlist as Playlist;
use iutnc\deefy\db\PlaylistService;
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function addPlaylist(Playlist $l): void
    {
        $_SESSION['user']['playlist'] = serialize($l);

        if (!isset($this->userPlaylists)) {
            $playlists = PlaylistService::getPlaylists();
            $this->userPlaylists = $playlists;
        }
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

    public function isAdmin(): bool
    {
        return $this->role == 100;
    }
}