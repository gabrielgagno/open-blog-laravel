<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Log;

class LogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info('openblog.request', ['request' => $request->all(), 'header' => $request->headers->all()]);
        return $next($request);
    }

    public function terminate($request, Response $response)
    {
        Log::info('openblog.response', ['response' => $response->getContent(), 'header' => $response->headers->all()]);
    }
}
