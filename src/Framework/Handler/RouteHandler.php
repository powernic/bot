<?php

namespace Powernic\Bot\Framework\Handler;

use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;

abstract class RouteHandler extends Handler implements AvailableMessageInterface, AvailableRouteInterface
{
    private string $name;

    protected Update $update;
    private string $route;
    protected Message $message;

    public function setMessage(Message $message): self
    {
        $this->message = $message;

        return $this;
    }


    public function setName(string $name)
    {
        $this->name = $name;
    }

    protected function getName(): string
    {
        return $this->name;
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }
    
    protected function getRoute(): string
    { 
        return $this->route;
    }

    /**
     * @return array<string,string>
     */
    private function getParameters(): array
    {
        $parameters = [];
        if (preg_match('/({.*?})/', $this->getName())) {
            $mask = preg_replace('/{(.*?)}/', '(?P<$1>\d+?)', $this->getName());
            $matches = [];
            if (preg_match('/^'.$mask.'$/', $this->getRoute(), $matches)) {
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $parameters[$key] = $value;
                    }
                }
            }
        }

        return $parameters;
    }

    protected function getParameter(string $name): string
    {
        $parameters = $this->getParameters();

        return $parameters[$name];
    }
}
