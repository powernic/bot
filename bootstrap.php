<?php

use Powernic\Bot\DependencyInjection\CommandHandlerPass;
use Powernic\Bot\DependencyInjection\BotExtension;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

require_once __DIR__ . '/vendor/autoload.php';

$file = __DIR__ . '/cache/container.php';

$containerConfigCache = new ConfigCache($file, true);

if (!$containerConfigCache->isFresh()) {
    $container = new ContainerBuilder();
    $extension = new BotExtension();
    $container->addCompilerPass(new CommandHandlerPass());
    $container->registerExtension($extension);
    $container->loadFromExtension($extension->getAlias());
    $container->compile(true);

    $dumper = new PhpDumper($container);
    $containerConfigCache->write(
        $dumper->dump(['class' => 'CachedContainer']),
        $container->getResources()
    );
}
require $file;

return new CachedContainer();
