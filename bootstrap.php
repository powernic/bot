<?php

use Powernic\Bot\DependencyInjection\CallbackHandlerPass;
use Powernic\Bot\DependencyInjection\CommandHandlerPass;
use Powernic\Bot\DependencyInjection\BotExtension;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Translation\DependencyInjection\TranslatorPass;
use Symfony\Component\Translation\DependencyInjection\TranslatorPathsPass;
use Symfony\Component\Validator\DependencyInjection\AddConstraintValidatorsPass;
use Symfony\Component\Validator\DependencyInjection\AddValidatorInitializersPass;

require_once __DIR__ . '/vendor/autoload.php';

$file = __DIR__ . '/cache/container.php';

$containerConfigCache = new ConfigCache($file, true);

if (!$containerConfigCache->isFresh()) {
    $container = new ContainerBuilder();
    $extension = new BotExtension();
    $container->addCompilerPass(new CommandHandlerPass());
    $container->addCompilerPass(new CallbackHandlerPass());
    $container->addCompilerPass(new AddConstraintValidatorsPass());
    $container->addCompilerPass(new AddValidatorInitializersPass());
    $container->addCompilerPass(new TranslatorPass());
    $container->addCompilerPass(new TranslatorPathsPass());
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
