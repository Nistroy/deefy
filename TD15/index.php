<?php
declare(strict_types=1);

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\dispatch\Dispatcher;
use iutnc\deefy\models\User;

require_once 'vendor/autoload.php';

session_start();
User::getCurrentUser();

ConnectionFactory::setConfig('conf.ini');

$d = new Dispatcher();
$d->run();


