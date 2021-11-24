<?php

namespace Powernic\Bot\Framework\Handler\Resolver;

use Powernic\Bot\Framework\Handler\AvailableUpdateInterface;
use TelegramBot\Api\Types\Update;

class TextHandlerResolver extends HandlerResolver
{
    public function resolve(): void
    {
        if ($this->container->has('handler.callback.text')) {
            /** @var AvailableUpdateInterface $callbackTextHandler */
            $callbackTextHandler = $this->container->get('handler.callback.text');
            $this->client->on(function (Update $update) use ($callbackTextHandler) {
                $this->setHandler($callbackTextHandler->setUpdate($update));
            }, function () {
                return true;
            });
        }
    }
}
