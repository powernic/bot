<?php

namespace Powernic\Bot\Framework;

use Powernic\Bot\Framework\DependencyInjection\CallbackHandlerPass;
use Powernic\Bot\Framework\DependencyInjection\CommandHandlerPass;
use Powernic\Bot\Framework\DependencyInjection\ContainerBuilderDebugDumpPass;
use Powernic\Bot\Framework\DependencyInjection\ServiceRepositoryCompilerPass;
use Powernic\Bot\Framework\DependencyInjection\TextHandlerPass;
use Powernic\Bot\Framework\Repository\ServiceEntityRepositoryInterface;
use Symfony\Component\Config\Resource\ClassExistenceResource;
use Symfony\Component\Console\DependencyInjection\AddConsoleCommandPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FrameworkBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CommandHandlerPass());
        $container->addCompilerPass(new CallbackHandlerPass());
        $container->addCompilerPass(new TextHandlerPass());
        $container->addCompilerPass(new ServiceRepositoryCompilerPass());
        $container->addCompilerPass(new ServiceLocatorTagPass());
        $container->addCompilerPass(new ContainerBuilderDebugDumpPass());
        $this->addCompilerPassIfExists($container, AddConsoleCommandPass::class, PassConfig::TYPE_BEFORE_REMOVING);
    }

    private function addCompilerPassIfExists(
        ContainerBuilder $container,
        string $class,
        string $type = PassConfig::TYPE_BEFORE_OPTIMIZATION,
        int $priority = 0
    ) {
        $container->addResource(new ClassExistenceResource($class));

        if (class_exists($class)) {
            $container->addCompilerPass(new $class(), $type, $priority);
        }
    }
}
