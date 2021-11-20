<?php

namespace Powernic\Bot\Framework\DependencyInjection;

use Powernic\Bot\CallbackHandler\CallbackHandlerInterface;
use Powernic\Bot\CallbackHandler\CallbackHandlerLoader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\TypedReference;

class CallbackHandlerPass implements CompilerPassInterface
{
    private string $callbackTag;

    public function __construct(string $commandTag = 'app.callback_handler')
    {
        $this->callbackTag = $commandTag;
    }

    public function process(ContainerBuilder $container)
    {
        $callbackHandlerServices = $container->findTaggedServiceIds($this->callbackTag);
        $lazyCallbackRefs = [];
        $lazyCallbackMap = [];
        foreach ($callbackHandlerServices as $id => $tags) {
            $definition = $container->getDefinition($id);
            $class = $container->getParameterBag()->resolveValue($definition->getClass());
            if (isset($tags[0]['callback'])) {
                $aliases = $tags[0]['callback'];
            } else {
                if (!$r = $container->getReflectionClass($class)) {
                    throw new InvalidArgumentException(
                        sprintf('Class "%s" used for service "%s" cannot be found.', $class, $id)
                    );
                }
                if (!$r->implementsInterface(CallbackHandlerInterface::class)) {
                    throw new InvalidArgumentException(
                        sprintf(
                            'The service "%s" tagged "%s" must be implements interface of "%s".',
                            $id,
                            $this->callbackTag,
                            CallbackHandlerInterface::class
                        )
                    );
                }
                $aliases = $class::getDefaultName();
            }
            $aliases = explode('|', $aliases ?? '');
            $callbackName = array_shift($aliases);
            $lazyCallbackRefs[$id] = new TypedReference($id, $class);
            $lazyCallbackMap[$callbackName] = $id;
            $definition->addMethodCall('setName', [$callbackName]);
        }

        $container
            ->register(CallbackHandlerLoader::class, CallbackHandlerLoader::class)
            ->setArguments([ServiceLocatorTagPass::register($container, $lazyCallbackRefs), $lazyCallbackMap]);
    }
}