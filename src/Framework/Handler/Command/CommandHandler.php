<?php

namespace Powernic\Bot\Framework\Handler\Command;

use Powernic\Bot\Framework\Handler\AvailableMessageInterface;
use Powernic\Bot\Framework\Handler\Handler;
use ReflectionException;
use ReflectionProperty;
use TelegramBot\Api\Types\Message;

abstract class CommandHandler extends Handler implements AvailableMessageInterface
{
    /**
     * @var string|null The default command name
     */
    protected static ?string $defaultName;
    protected Message $message;

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

    /**
     * @param Message $message
     * @return self
     */
    public function setMessage(Message $message): self
    {
        $this->message = $message;

        return $this;
    }


}
