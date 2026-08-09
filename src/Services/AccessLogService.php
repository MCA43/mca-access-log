<?php

namespace Mca\AccessLog\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Mca\AccessLog\Models\AccessLog;
use Symfony\Component\HttpFoundation\Response;

class AccessLogService
{
    public function shouldCapture(Request $request): bool
    {
        if (! config('access-log.enabled', true) || ! config('access-log.logging.enabled', true)) {
            return false;
        }

        $method = strtoupper($request->getMethod());
        $skipMethods = config('access-log.logging.skip_methods', ['OPTIONS']);
        if (is_array($skipMethods) && in_array($method, $skipMethods, true)) {
            return false;
        }

        $path = ltrim($request->path(), '/');
        $skipPaths = config('access-log.logging.skip_paths', []);
        if (is_array($skipPaths)) {
            foreach ($skipPaths as $pattern) {
                if (! is_string($pattern) || $pattern === '') {
                    continue;
                }
                if ($request->is(trim($pattern, '/'))) {
                    return false;
                }
            }
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $skipExt = config('access-log.logging.skip_extensions', []);
        if ($extension !== '' && is_array($skipExt) && in_array($extension, $skipExt, true)) {
            return false;
        }

        $rate = (float) config('access-log.logging.sample_rate', 1.0);
        if ($rate < 1.0) {
            if ($rate <= 0.0) {
                return false;
            }
            if (mt_rand() / mt_getrandmax() > $rate) {
                return false;
            }
        }

        return true;
    }

    public function record(
        Request $request,
        ?Response $response = null,
        ?float $startedAt = null,
        bool $isBlocked = false,
        array $meta = [],
    ): ?AccessLog {
        if (! $isBlocked && ! $this->shouldCapture($request)) {
            return null;
        }

        $maxPath = (int) config('access-log.logging.max_path_length', 500);
        $maxUa = (int) config('access-log.logging.max_ua_length', 512);
        $path = '/'.ltrim($request->path(), '/');
        if (strlen($path) > $maxPath) {
            $path = substr($path, 0, $maxPath);
        }

        $ua = null;
        if (config('access-log.logging.store_user_agent', true)) {
            $ua = substr((string) $request->userAgent(), 0, $maxUa) ?: null;
        }

        $referer = null;
        if (config('access-log.logging.store_referer', true)) {
            $referer = substr((string) $request->headers->get('referer'), 0, 500) ?: null;
        }

        $duration = null;
        if ($startedAt !== null) {
            $duration = (int) max(0, round((microtime(true) - $startedAt) * 1000));
        }

        $status = $response?->getStatusCode() ?? ($isBlocked ? (int) config('firewall.protection.block_status', 403) : 0);

        return AccessLog::query()->create([
            'ip' => (string) ($request->ip() ?: '0.0.0.0'),
            'method' => strtoupper($request->getMethod()),
            'path' => $path,
            'route_name' => optional($request->route())->getName(),
            'status_code' => $status,
            'user_id' => $request->user()?->getAuthIdentifier(),
            'user_agent' => $ua,
            'referer' => $referer,
            'duration_ms' => $duration,
            'is_blocked' => $isBlocked,
            'meta' => $meta === [] ? null : $meta,
            'created_at' => now(),
        ]);
    }

    public function recordBlocked(string $ip, string $path, string $method, array $meta = []): AccessLog
    {
        return AccessLog::query()->create([
            'ip' => $ip,
            'method' => strtoupper($method),
            'path' => substr($path !== '' ? (str_starts_with($path, '/') ? $path : '/'.$path) : '/', 0, 500),
            'route_name' => null,
            'status_code' => (int) config('firewall.protection.block_status', 403),
            'user_id' => null,
            'user_agent' => null,
            'referer' => null,
            'duration_ms' => null,
            'is_blocked' => true,
            'meta' => $meta === [] ? ['source' => 'firewall'] : $meta,
            'created_at' => now(),
        ]);
    }

    /** @return array{hits: int, blocked: int, unique_paths: int, last_seen: ?Carbon, methods: array<string, int>, statuses: array<string, int>} */
    public function summarizeIp(string $ip, ?Carbon $since = null): array
    {
        $query = AccessLog::query()->forIp($ip);
        if ($since !== null) {
            $query->where('created_at', '>=', $since);
        }

        $hits = (clone $query)->count();
        $blocked = (clone $query)->blocked()->count();
        $uniquePaths = (clone $query)->distinct('path')->count('path');
        $lastSeen = (clone $query)->max('created_at');

        $methods = (clone $query)
            ->select('method', DB::raw('count(*) as aggregate'))
            ->groupBy('method')
            ->pluck('aggregate', 'method')
            ->map(fn ($v) => (int) $v)
            ->all();

        $statuses = (clone $query)
            ->select('status_code', DB::raw('count(*) as aggregate'))
            ->groupBy('status_code')
            ->pluck('aggregate', 'status_code')
            ->map(fn ($v) => (int) $v)
            ->all();

        return [
            'hits' => $hits,
            'blocked' => $blocked,
            'unique_paths' => $uniquePaths,
            'last_seen' => $lastSeen ? Carbon::parse($lastSeen) : null,
            'methods' => $methods,
            'statuses' => $statuses,
        ];
    }

    /** @return Collection<int, object> */
    public function topIps(int $limit = 20, ?Carbon $since = null): Collection
    {
        $query = AccessLog::query()
            ->select('ip', DB::raw('count(*) as hits'), DB::raw('sum(case when is_blocked = 1 then 1 else 0 end) as blocked'), DB::raw('max(created_at) as last_seen'))
            ->groupBy('ip')
            ->orderByDesc('hits')
            ->limit($limit);

        if ($since !== null) {
            $query->where('created_at', '>=', $since);
        }

        return $query->get();
    }

    public function purgeOlderThan(int $days): int
    {
        $days = max(1, $days);

        return AccessLog::query()
            ->where('created_at', '<', now()->subDays($days))
            ->delete();
    }

    public function firewallAvailable(): bool
    {
        return function_exists('mca_firewall') && class_exists(\Mca\Firewall\Services\FirewallService::class);
    }
}
