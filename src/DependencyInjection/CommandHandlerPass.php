<?php

namespace Powernic\Bot\DependencyInjection;

use Powernic\Bot\CommandHandler\CommandHandlerInterface;
use Powernic\Bot\CommandHandler\CommandHandlerLoader;
use ReflectionException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\TypedReference;

class CommandHandlerPass implements CompilerPassInterface
{
    private string $commandTag;

    public function __construct(string $commandTag = 'app.command_handler')
    {
        $this->commandTag = $commandTag;
    }

    /**
     * @throws ReflectionException
     */
    public function process(ContainerBuilder $container): void
    {
        $commandHandlerServices = $container->findTaggedServiceIds($this->commandTag);
        $lazyCommandRefs = [];
        $lazyCommandMap = [];
        foreach ($commandHandlerServices as $id => $tags) {
            $definition = $container->getDefinition($id);
            $class = $container->getParameterBag()->resolveValue($definition->getClass());
            if (isset($tags[0]['command'])) {
                $aliases = $tags[0]['command'];
            } else {
                if (!$r = $container->getReflectionClass($class)) {
                    throw new InvalidArgumentException(
                        sprintf('Class "%s" used for service "%s" cannot be found.', $class, $id)
                    );
                }
                if (!$r->implementsInterface(CommandHandlerInterface::class)) {
                    throw new InvalidArgumentException(
                        sprintf(
                            'The service "%s" tagged "%s" must be implements interface of "%s".',
                            $id,
                            $this->commandTag,
                            CommandHandlerInterface::class
                        )
                    );
                }
                $aliases = $class::getDefaultName();
            }
            $aliases = explode('|', $aliases ?? '');
            $commandName = array_shift($aliases);
            $lazyCommandRefs[$id] = new TypedReference($id, $class);
            $lazyCommandMap[$commandName] = $id;
        }

        $container
            ->register(CommandHandlerLoader::class, CommandHandlerLoader::class)
            ->setArguments([ServiceLocatorTagPass::register($container, $lazyCommandRefs), $lazyCommandMap]);
    }
}
