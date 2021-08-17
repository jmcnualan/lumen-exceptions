<?php

namespace Dmn\Exceptions\Example\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
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
        $this->validate($request, ['field' => 'required']);
    }
}
