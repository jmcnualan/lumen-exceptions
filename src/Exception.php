<?php

namespace Dmn\Exceptions;

use Exception as BaseException;
use Illuminate\Http\JsonResponse;
use Throwable;

class Exception extends BaseException
{
    protected $code = 'unexpected_error';

    protected $message = 'Unexpected error.';

    /**
     * Construct
     *
     * @param string $message
     * @param integer $code
     * @param Throwable $previous
     */
    public function __construct(
        $message = '',
        $code = 0,
        Throwable $previous = null
    ) {
        $message = $message == '' ? ($this->message ?? '') : $message;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get error description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description ?? $this->getMessage();
    }

    /**
     * Get meta
     *
     * @return array
     */
    public function getMeta(): array
    {
        return [];
    }

    /**
     * Render an exception to HTTP Response
     *
     * @return void
     */
    public function render()
    {
        $response = [
            'error' => $this->code,
            'message' => $this->getMessage(),
            'error_description' => $this->getDescription(),
        ];

        $this->mergeMeta($response);
        $this->mergeErrorResponse($response);

        return new JsonResponse($response, $this->httpStatusCode ?? 400);
    }

    /**
     * Merge error response for validation exception
     *
     * @param array $response
     * @return void
     */
    private function mergeErrorResponse(array &$response): void
    {
        $previous = $this->getPrevious();

        if ($previous != null) {
            if (method_exists($previous, 'errors')) {
                $response['errors'] = $previous->errors();
                $this->setReferences($response);
            }
        }
    }

    /**
     * Set reference if any
     *
     * @param array $response
     *
     * @return void
     */
    private function setReferences(array &$response): void
    {
        $group      = config('validation.default_group');
        $references = (array) config('validation.references.' . $group);

        foreach ($references as $reference => $data) {
            $pattern = '/^' . $reference . '(\.\S+)?$/i';
            $matches = preg_grep($pattern, array_keys($response['errors']));
            if (count($matches) > 0) {
                $route = route('reference.' . $group . '.' . $reference);
                $response['meta']['references'][$reference] = $route;
            }
        }
    }

    /**
     * Merge meta to response
     *
     * @param array $response
     *
     * @return void
     */
    private function mergeMeta(array &$response): void
    {
        if (count($this->getMeta()) < 1) {
            return;
        }

        $response['meta'] = $this->getMeta();
    }
}
