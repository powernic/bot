<?php

use Powernic\Bot\Application;
use Powernic\Bot\Kernel;
use function Sentry\captureException;
use function Sentry\init;

require __DIR__ . '/vendor/autoload.php';
$kernel = new Kernel('dev', true);
$app = new Application($kernel);
init(['dsn' => $_ENV['SENTRY_DSN'] ]);
try {
    $app->boot();
} catch (\Throwable $exception) {
    captureException($exception);
}
