<?php

namespace Powernic\Bot\Framework\Handler\Resolver;

use TelegramBot\Api\Types\Update;

class TextHandlerResolver extends HandlerResolver
{
    public function resolve(): void
    {
        if ($this->container->has('handler.resolver.text')) {
            /** @var TextHandlerResolverInterface $textHandlerResolver */
            $textHandlerResolver = $this->container->get('handler.resolver.text');
            $this->client->on(function (Update $update) use ($textHandlerResolver) {
                $message = $update->getMessage();
                $action = $textHandlerResolver->resolve($message);
                $handlerLoader = $this->container->get('handler.text.loader');
                foreach ($handlerLoader->getNames() as $actionMask) {
                    if ($this->isValidHandlerParameters($actionMask, $action)) {
                        $this->setHandler($handlerLoader->get($actionMask)->setMessage($message));

                        return;
                    }
                }
            }, function () {
                return true;
            });
        }
    }
}