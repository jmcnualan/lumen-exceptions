<?php

use Dmn\Exceptions\Controllers\ReferenceController;

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(
    ['prefix' => 'reference'],
    function ($router) {
        $groups = config('validation.references');
        foreach ($groups as $group => $references) {
            foreach ($references as $reference => $data) {
                $router->get(
                    $group . '/'  . $reference,
                    [
                        'as' => 'reference.' . $group . '.' . $reference,
                        function () use ($data) {
                            $controller = app(ReferenceController::class);
                            return $controller->index($data);
                        }
                    ]
                );
            }
        }
    }
);
