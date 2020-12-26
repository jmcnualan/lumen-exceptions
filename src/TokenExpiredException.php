<?php

namespace Dmn\Exceptions;

use Dmn\Exceptions\Exception;

class TokenExpiredException extends Exception
{
    protected $httpStatusCode = 401;

    protected $code = 'token_expired';

    public $message = 'Token has expired.';
}
