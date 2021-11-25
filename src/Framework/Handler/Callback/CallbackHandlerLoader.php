<?php

namespace Powernic\Bot\Framework\Handler\Callback;

use Powernic\Bot\Framework\Handler\AvailableCallbackQueryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\DependencyInjection\ServiceLocator;

class CallbackHandlerLoader implements ContainerInterface
{
    private ServiceLocator $container;
    private array $callbackMap;

    /**
     * @param array $callbackMap An array with command names as keys and service ids as values
     */
    public function __construct(ServiceLocator $container, array $callbackMap)
    {
        $this->container = $container;
        $this->callbackMap = $callbackMap;
    }

    /**
     * @param string $id
     * @return AvailableCallbackQueryInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function get(string $id): AvailableCallbackQueryInterface
    {
        if (!$this->has($id)) {
            throw new CommandNotFoundException(sprintf('Command Handler "%s" does not exist.', $id));
        }

        return $this->container->get($this->callbackMap[$id]);
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $id): bool
    {
        return isset($this->callbackMap[$id]) && $this->container->has($this->callbackMap[$id]);
    }

    /**
     * @return array<string, CallbackHandler> All registered callback handler references
     */
    public function getRefs(): array
    {
        $providedTypes = $this->container->getProvidedServices();
        $refs = [];
        foreach ($this->callbackMap as $callback => $serviceId) {
            /** @var CallbackHandler $providedType */
            $providedType = $providedTypes[$serviceId];
            $refs[$callback] = $providedType;
        }

        return $refs;
    }
}