<?php

namespace Powernic\Bot\Framework\Handler\Resolver;

use Exception;
use Powernic\Bot\Framework\Handler\HandlerInterface;

class ContainerHandlerResolver
{
    /**
     * @var HandlerResolver[]
     */
    private array $handlerResolvers;

    /**
     * @param HandlerResolver[] $handlerResolvers
     */
    public function __construct(array $handlerResolvers)
    {
        $this->handlerResolvers = $handlerResolvers;
    }

    public function getHandlerResolver(string $className): ?HandlerResolver
    {
        foreach ($this->handlerResolvers as $handlerResolver) {
            if ($handlerResolver::class === $className) {
                return $handlerResolver;
            }
        }
        return null;
    }

    public function resolve(): void
    {
        foreach ($this->handlerResolvers as $handlerResolver) {
            $handlerResolver->resolve();
        }
    }

    /**
     * @throws Exception
     */
    public function getHandler(): HandlerInterface
    {
        foreach ($this->handlerResolvers as $handlerResolver) {
            if ($handlerResolver->hasHandler()) {
                return $handlerResolver->getHandler();
            }
        }
        throw new Exception("Handler is not resolved");
    }
}
