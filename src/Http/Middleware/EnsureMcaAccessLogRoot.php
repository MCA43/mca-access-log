<?php

namespace Mca\AccessLog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mca\Permission\Services\PermissionService;
use Symfony\Component\HttpFoundation\Response;

class EnsureMcaAccessLogRoot
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            abort(403);
        }

        if (config('access-log.access.use_permission_root', true) && class_exists(PermissionService::class)) {
            if (app(PermissionService::class)->isRoot($user)) {
                return $next($request);
            }

            abort(403, mca_alog('errors.root_only'));
        }

        $column = (string) config('access-log.access.role_column', 'role_id');
        $rootRole = (string) config('access-log.access.root_role', 'root');
        $value = $user->{$column} ?? null;

        if ($column === 'role_id' && $value && class_exists(\Mca\Permission\Models\Role::class)) {
            if (\Mca\Permission\Models\Role::query()->whereKey($value)->where('is_root', true)->exists()) {
                return $next($request);
            }
        } elseif ((string) $value === $rootRole) {
            return $next($request);
        }

        abort(403, mca_alog('errors.root_only'));
    }
}
