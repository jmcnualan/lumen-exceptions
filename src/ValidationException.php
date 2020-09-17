<?php

namespace Dmn\Exceptions;

use Dmn\Exceptions\Exception;
use Throwable;

class ValidationException extends Exception
{
    protected $httpStatusCode = 422;

    protected $code = 'unprocessable_entity';

    /**
     * @inheritDoc
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
