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
                        if (
                            $callbackId === $callbackQuery->getData()
                            || $this->isValidHandlerParameters($callbackId, $callbackQuery->getData())
                        ) {
                            $this->setHandler(
                                $callbackHandlerLoader->get($callbackId)->setCallbackQuery($callbackQuery)
                            );
                            break;
                        }
                    }
                }
            );
        }
    }

    private function isValidHandlerParameters(string $callbackMask, string $data): bool
    {
        if (preg_match('/({.*?})/', $callbackMask)) {
            $mask = preg_replace('/{.*?}/', '(\d+?)', $callbackMask);
            if (preg_match('/^' . $mask . '$/', $data)) {
                return true;
            }
        }

        return false;
    }
}
