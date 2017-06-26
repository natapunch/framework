<?php
/**
 *
 */

namespace Punchenko\Framework\Middleware;

class Kernel
{
    protected $routeMiddleware = [
        'auth' => Autenticate::class,
        'is_admin' => IsAdmin::class,

    ];

    protected $middlewares = ['auth', 'is_admin'];
}