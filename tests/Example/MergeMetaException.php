<?php

namespace Dmn\Exceptions\Example;

use Dmn\Exceptions\Exception;

class MergeMetaException extends Exception
{
    /**
     * @inheritDoc
     */
    public function getMeta(): array
    {
        return [
            'test' => 'test meta',
        ];
    }
}
