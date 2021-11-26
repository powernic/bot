<?php

namespace Powernic\Bot\Framework\Handler\Resolver;

use TelegramBot\Api\Types\CallbackQuery;

class CallbackHandlerResolver extends HandlerResolver
{

    public function resolve(): void
    {
        if ($this->container->has('handler.callback.loader')) {
            $callbackHandlerLoader = $this->container->get('handler.callback.loader');
            $callbacks = $callbackHandlerLoader->getRefs();
            $this->client->callbackQuery(
                function (CallbackQuery $callbackQuery) use ($callbackHandlerLoader, $callbacks) {
                    foreach ($callbacks as $callbackId => $callbackHandler) {
                        $route = $callbackQuery->getData();
                        if (
                            $callbackId === $route
                            || $this->isValidHandlerParameters($callbackId, $route)
                        ) {
                            $this->setHandler(
                                $callbackHandlerLoader->get($callbackId)->setRoute($route)->setMessage(
                                    $callbackQuery->getMessage()
                                )
                            );
                            break;
                        }
                    }
                }
            );
        }
    }
}
