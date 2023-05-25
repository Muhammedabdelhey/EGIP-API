<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ORMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $middleware1, $middleware2)
    {

        // Call first middleware 
        $response = app($middleware1)->handle($request, $next);
        // If the first middleware  returns 401, call the second middleware
        if ($response->getStatusCode() === 401) {
            $response = app($middleware2)->handle($request, $next);
        }
        // Return the response
        return $response;
    }
}
