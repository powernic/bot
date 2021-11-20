<?php

use Powernic\Bot\Application;
use Powernic\Bot\Kernel;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/vendor/autoload.php';
$kernel = new Kernel('dev', true);
$app = new Application($kernel);
$app->boot();