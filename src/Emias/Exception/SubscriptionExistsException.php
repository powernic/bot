<?php

namespace Powernic\Bot\Emias\Exception;

use Exception;

class SubscriptionExistsException extends Exception
{
    protected $message = 'exception.subscription.exists';
}
