<?php

namespace Dmn\Exceptions;

use Dmn\Exceptions\Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException as Previous;

class ModelNotFoundException extends Exception
{
    protected $httpStatusCode = 404;

    protected $code = 'resource_not_found';

    /**
     * Constructor
     *
     * @param Previous $previous
     */
    public function __construct(Previous $previous)
    {
        parent::__construct(
            $this->buildMessage($previous),
            $previous->getCode(),
            $previous
        );
    }

    /**
     * Build message
     *
     * @param Previous $exception
     *
     * @return string
     */
    private function buildMessage(Previous $exception): string
    {
        $model    = $exception->getModel();
        $resource = 'Resource';

        if (defined("$model::RESOURCE_NAME")) {
            $resource = $model::RESOURCE_NAME;
        }

        return $resource . ' not found.';
    }
}
