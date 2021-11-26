<?php

namespace Powernic\Bot\Framework\DependencyInjection;

use Powernic\Bot\Framework\Handler\HandlerInterface;
use Powernic\Bot\Framework\Handler\Text\TextHandlerLoader;
use ReflectionException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\TypedReference;

class TextHandlerPass implements CompilerPassInterface
{
    private string $commandTag;

    public function __construct(string $commandTag = 'app.text_handler')
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
            if (isset($tags[0]['action'])) {
                $aliases = $tags[0]['action'];
            } else {
                if (!$r = $container->getReflectionClass($class)) {
                    throw new InvalidArgumentException(
                        sprintf('Class "%s" used for service "%s" cannot be found.', $class, $id)
                    );
                }
                if (!$r->implementsInterface(HandlerInterface::class)) {
                    throw new InvalidArgumentException(
                        sprintf(
                            'The service "%s" tagged "%s" must be implements interface of "%s".',
                            $id,
                            $this->commandTag,
                            HandlerInterface::class
                        )
                    );
                }
                $aliases = $class::getDefaultName();
            }
            $aliases = explode('|', $aliases ?? '');
            $actionName = array_shift($aliases);
            $lazyCommandRefs[$id] = new TypedReference($id, $class);
            $lazyCommandMap[$actionName] = $id;
            $definition->addMethodCall('setAction', [$actionName]);
            $definition->addMethodCall('setName', [$actionName]);
        }

        $container
            ->register('handler.text.loader', TextHandlerLoader::class)
            ->setPublic(true)
            ->setArguments([ServiceLocatorTagPass::register($container, $lazyCommandRefs), $lazyCommandMap]);
    }
}
