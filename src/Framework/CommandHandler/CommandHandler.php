<?php

namespace Powernic\Bot\Framework\CommandHandler;

use Powernic\Bot\Emias\Policy\CommandHandler\EmiasPolicyCommandHandler;
use ReflectionException;
use ReflectionProperty;

abstract class CommandHandler implements CommandHandlerInterface
{
    /**
     * @var string|null The default command name
     */
    protected static ?string $defaultName;

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
}
