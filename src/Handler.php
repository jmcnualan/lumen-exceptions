<?php

namespace Dmn\Exceptions;

use Dmn\Exceptions\MethodNotAllowedHttpException as AppMethodNotAllowedHttpException;
use Dmn\Exceptions\ModelNotFoundException as AppModelNotFoundException;
use Dmn\Exceptions\NotFoundHttpException as AppNotFoundHttpException;
use Dmn\Exceptions\ThrottleRequestsException as AppThrottleRequestsException;
use Dmn\Exceptions\ValidationException as AppValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * @inheritDoc
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * @inheritDoc
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $this->customException($exception));
    }

    /**
     * Custom exceptions
     *
     * @param Throwable $exception
     *
     * @return Throwable
     */
    protected function customException(Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return new AppModelNotFoundException($exception);
        }

        if ($exception instanceof NotFoundHttpException) {
            return new AppNotFoundHttpException();
        }

        if ($exception instanceof ValidationException) {
            return new AppValidationException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return new AppMethodNotAllowedHttpException();
        }

        if ($exception instanceof ThrottleRequestsException) {
            return new AppThrottleRequestsException(
                $exception->getMessage(),
                $exception->getPrevious(),
                $exception->getHeaders(),
                $exception->getCode(),
            );
        }

        return $exception;
    }
}
