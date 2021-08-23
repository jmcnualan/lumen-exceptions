<?php

namespace Dmn\Exceptions;

class ESNoHitsFoundException extends Exception
{
    protected $httpStatusCode = 404;

    protected $code = 'es_no_hits';

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
     * @param string $index
     * @return string
     */
    private function buildMessage(string $index): string
    {
        return $index . ' not found.';
    }
}
