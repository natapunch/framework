<?php
/**
 * Created by PhpStorm.
 * User: jenn
 * Date: 19.04.17
 * Time: 17:48
 */

namespace Punchenko\Framework\Middleware;


use Closure;
use Punchenko\Framework\Request\Request;

class Autenticate
{
    public function handle(Request $request, Closure $next, $controller){
     $responce=$controller;
    }
return $responce=$next($request)
}