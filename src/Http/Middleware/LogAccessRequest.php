<?php

namespace Mca\AccessLog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mca\AccessLog\Services\AccessLogService;
use Symfony\Component\HttpFoundation\Response;

class LogAccessRequest
{
    public function __construct(private readonly AccessLogService $logs) {}

    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('mca_access_log_started_at', microtime(true));

        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if (! $this->logs->shouldCapture($request)) {
            return;
        }

        $started = $request->attributes->get('mca_access_log_started_at');
        $startedAt = is_float($started) || is_int($started) ? (float) $started : null;

        $this->logs->record($request, $response, $startedAt, false);
    }
}
