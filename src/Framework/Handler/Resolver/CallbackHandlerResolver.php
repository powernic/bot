<?php

namespace Powernic\Bot\Framework\Handler\Resolver;

use Powernic\Bot\Framework\Handler\HandlerInterface;
use TelegramBot\Api\Types\CallbackQuery;
use TelegramBot\Api\Types\Message;

class CallbackHandlerResolver extends HandlerResolver
{

    public function resolve(): void
    {
        $this->client->callbackQuery(
            function (CallbackQuery $callbackQuery) {
                $handler = $this->matchHandler($callbackQuery->getData(), $callbackQuery->getMessage());
                $this->setHandler($handler);
            }
        );
    }

    private function isRouteMatched(string $callbackMask, string $route): bool
    {
        return $callbackMask === $route || $this->isValidHandlerParameters($callbackMask, $route);
    }

    public function matchHandler(string $route, Message $message): ?HandlerInterface
    {
        if ($this->container->has('handler.callback.loader')) {
            $callbackHandlerLoader = $this->container->get('handler.callback.loader');
            $callbacks = $callbackHandlerLoader->getRefs();
            foreach ($callbacks as $callbackId => $callbackHandler) {
                if ($this->isRouteMatched($callbackId, $route)) {
                    return $callbackHandlerLoader->get($callbackId)->setRoute($route)->setMessage($message);
                }
            }
        }
        return null;
    }
}
