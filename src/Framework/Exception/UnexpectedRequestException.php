<?php

namespace Powernic\Bot\Framework\Exception;

use Exception;

class UnexpectedRequestException extends Exception
{
    protected $message = 'exception.unexpected.request';
}
