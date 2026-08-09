<?php

namespace Mca\AccessLog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mca\AccessLog\Support\McaAccessLogLocale;
use Symfony\Component\HttpFoundation\Response;

class SetMcaAccessLogLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        McaAccessLogLocale::apply();

        return $next($request);
    }
}
