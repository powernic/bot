<?php

use Powernic\Bot\Application;
use Symfony\Component\DependencyInjection\Container;

date_default_timezone_set('Europe/Moscow');
/** @var false|Container $container */
$container = include(__DIR__ . "/bootstrap.php");

/** @var Application $bot */
$bot = $container->get("app.bot");
