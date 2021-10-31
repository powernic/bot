<?php

use Powernic\Bot\Application;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

date_default_timezone_set('Europe/Moscow');
/** @var false|Container $container */
$container = include(__DIR__ . "/bootstrap.php");

if ($container->has("app.bot")) {
    /** @var Application $bot */
    $bot = $container->get("app.bot");
    $request = Request::createFromGlobals();
    $bot->run();
}
