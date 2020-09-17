<?php

namespace Dmn\Exceptions;

use Dmn\Exceptions\Exception;

class MethodNotAllowedHttpException extends Exception
{
    protected $httpStatusCode = 405;

    protected $code = 'method_not_allowed';

    public $message = 'Method not allowed.';
}
