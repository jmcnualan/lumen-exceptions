<?php

namespace Dmn\Exceptions;

use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class ThrottleRequestsException extends TooManyRequestsHttpException
{
    /**
     * Create a new throttle requests exception instance.
     *
     * @param  string|null  $message
     * @param  \Throwable|null  $previous
     * @param  array  $headers
     * @param  int  $code
     * @return void
     */
    public function __construct($message = null, Throwable $previous = null, array $headers = [], $code = 0)
    {
        parent::__construct(null, $message, $previous, $code, $headers);
    }

    /**
     * Render an exception to HTTP Response
     *
     * @return void
     */
    public function render()
    {
        $response = [
            'error' => 'too_many_requests',
            'message' => $this->getMessage(),
            'error_description' => $this->getMessage(),
        ];

        return response()->json(
            $response,
            $this->getStatusCode(),
            $this->getHeaders()
        );
    }
}
