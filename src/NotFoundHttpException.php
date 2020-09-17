<?php

namespace Dmn\Exceptions;

use Dmn\Exceptions\Exception;

class NotFoundHttpException extends Exception
{
    protected $httpStatusCode = 404;

    protected $code = 'http_not_found';

    public $message = 'Route not found.';

    protected $description = 'Route not found. Please check the URI.';
}
