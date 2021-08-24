<?php

namespace Dmn\Exceptions;

class ResourceNotFoundException extends Exception
{
    protected $httpStatusCode = 404;

    protected $code = 'resource_not_found';

    /**
     * Constructor
     *
     * @param string $index
     */
    public function __construct(string $index)
    {
        parent::__construct(
            $this->buildMessage($index)
        );
    }


    /**
     * Build message
     *
     * @param string $resource
     * @return string
     */
    private function buildMessage(string $resource): string
    {
        return $resource . ' not found.';
    }
}
