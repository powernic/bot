<?php

namespace Powernic\Bot\Framework\Handler\Callback;

use Powernic\Bot\Framework\Handler\AvailableCallbackQueryInterface;
use Powernic\Bot\Framework\Handler\Handler;
use ReflectionException;
use ReflectionProperty;
use TelegramBot\Api\Types\CallbackQuery;
use TelegramBot\Api\Types\Update;

abstract class CallbackHandler extends Handler implements AvailableCallbackQueryInterface
{

    private string $name;

    /**
     * @var string|null The default command name
     */
    protected static ?string $defaultName;
    protected CallbackQuery $callbackQuery;
    protected Update $update;

    public function textHandle(): void
    {
    }

    /**
     * @return string|null The default command name or null when no default name is set
     * @throws ReflectionException
     */
    public static function getDefaultName(): ?string
    {
        $class = static::class;

        $r = new ReflectionProperty($class, 'defaultName');

        return $class === $r->class ? static::$defaultName : null;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setCallbackQuery(CallbackQuery $callbackQuery): self
    {
        $this->callbackQuery = $callbackQuery;

        return $this;
    }

    /**
     * @param Update $update
     * @return $this
     */
    public function setUpdate(Update $update): self
    {
        $this->update = $update;

        return $this;
    }

    protected function getUpdate(): Update
    {
        return $this->update;
    }

    /**
     * @return array<string,string>
     */
    private function getParameters(): array
    {
        $parameters = [];
        $data = $this->callbackQuery->getData();
        if (preg_match('/({.*?})/', $this->name)) {
            $mask = preg_replace('/{(.*?)}/', '(?P<$1>\d+?)', $this->name);
            $matches = [];
            if (preg_match('/^' . $mask . '$/', $data, $matches)) {
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
