<?php
/**
 * Created by PhpStorm.
 * User: jenn
 * Date: 19.04.17
 * Time: 15:51
 */

namespace Punchenko\Framework\Middleware;


use Closure;
use Punchenko\Framework\Request\Request;

interface Middleware
{
    public function __construct();

    public function handle(Request $request, Closure $next);
}