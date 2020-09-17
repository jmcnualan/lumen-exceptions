<?php

namespace Dmn\Exceptions\Example\Controllers;

use Exception;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class TestController extends Controller
{
    /**
     * Test route
     *
     * @param Request $request
     *
     * @return void
     */
    public function test(Request $request): void
    {
        $this->validate($request, ['name' => 'required']);
    }
}
