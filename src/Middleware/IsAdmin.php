<?php
/**
 * Created by PhpStorm.
 * User: jenn
 * Date: 19.04.17
 * Time: 17:19
 */

namespace Punchenko\Framework\Middleware;


use Closure;
use Punchenko\Framework\Request\Request;

class IsAdmin implements Middleware
{
    protected $auth;
    /**
     * IsAdmin constructor.
     */
    public function __construct($auth)
    {
      $this->auth=$auth;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($request->){
            return redirect()->route('404');
        }
    }
    return $next($request);
}