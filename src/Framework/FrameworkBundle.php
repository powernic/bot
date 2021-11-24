<?php

namespace Powernic\Bot\Framework;

use Powernic\Bot\Framework\DependencyInjection\CallbackHandlerPass;
use Powernic\Bot\Framework\DependencyInjection\CommandHandlerPass;
use Powernic\Bot\Framework\DependencyInjection\ContainerBuilderDebugDumpPass;
use Powernic\Bot\Framework\DependencyInjection\ServiceRepositoryCompilerPass;
use Powernic\Bot\Framework\Repository\ServiceEntityRepositoryInterface;
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
        $container->addCompilerPass(new ServiceRepositoryCompilerPass());
        $container->addCompilerPass(new ServiceLocatorTagPass());
        $container->addCompilerPass(new ContainerBuilderDebugDumpPass());
    }
}
