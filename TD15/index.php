<?php
declare(strict_types=1);

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\dispatch\Dispatcher;

require_once 'vendor/autoload.php';

session_start();
if(!isset($_SESSION['user'])){
    $t = [
        "email" =>"",
        "age" =>0,
        "genre" =>"",
        "playlist" =>null
    ];
    $_SESSION['user'] = $t;
}

ConnectionFactory::setConfig('conf.ini');

$d = new Dispatcher();
$d->run();


