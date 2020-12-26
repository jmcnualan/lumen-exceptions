<?php

namespace Dmn\Exceptions;

use Dmn\Exceptions\Exception;

class UnauthorizedException extends Exception
{
    protected $httpStatusCode = 401;

    protected $code = 'unauthorized';

    public $message = 'Unauthorized.';
}
