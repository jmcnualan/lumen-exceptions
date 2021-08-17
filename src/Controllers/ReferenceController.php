<?php

namespace Dmn\Exceptions\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;

class ReferenceController
{
    /**
     * List all data set
     *
     * @return JsonResource
     */
    public function index(array $data): JsonResource
    {
        return new JsonResource($data);
    }
}
