<?php

namespace Dmn\Exceptions;

class ForbiddenException extends Exception
{
    protected $httpStatusCode = 403;

    protected $code = 'forbidden';

    public $message = 'You don\'t have permission to access this resource.';
}