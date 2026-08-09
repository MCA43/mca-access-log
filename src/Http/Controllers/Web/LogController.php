<?php

namespace Mca\AccessLog\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Mca\AccessLog\Models\AccessLog;
use Mca\AccessLog\Services\AccessLogService;
use Mca\AccessLog\Support\McaAccessLogView;

class LogController extends Controller
{
    public function __construct(private readonly AccessLogService $logs) {}

    public function index(Request $request): View
    {
        $ip = trim((string) $request->query('ip', ''));
        $method = strtoupper(trim((string) $request->query('method', '')));
        $status = trim((string) $request->query('status', ''));
        $blocked = (string) $request->query('blocked', '');
        $q = trim((string) $request->query('q', ''));

        $query = AccessLog::query()->latest('id');

        if ($ip !== '') {
            $query->forIp($ip);
        }

        if ($method !== '') {
            $query->where('method', $method);
        }

        if ($status !== '' && ctype_digit($status)) {
            $query->where('status_code', (int) $status);
        }

        if ($blocked === '1') {
            $query->blocked();
        } elseif ($blocked === '0') {
            $query->where('is_blocked', false);
        }

        if ($q !== '') {
            $query->where(function ($builder) use ($q): void {
                $builder->where('path', 'like', '%'.$q.'%')
                    ->orWhere('route_name', 'like', '%'.$q.'%')
                    ->orWhere('user_agent', 'like', '%'.$q.'%');
            });
        }

        return McaAccessLogView::render('logs.index', [
            'logs' => $query->paginate(30)->withQueryString(),
            'filters' => compact('ip', 'method', 'status', 'blocked', 'q'),
            'topIps' => $this->logs->topIps(8),
            'firewallAvailable' => $this->logs->firewallAvailable(),
            'counts' => [
                'total' => AccessLog::query()->count(),
                'blocked' => AccessLog::query()->blocked()->count(),
            ],
        ]);
    }

    public function ip(string $ip): View
    {
        $summary = $this->logs->summarizeIp($ip);
        $recent = AccessLog::query()->forIp($ip)->latest('id')->paginate(30);

        return McaAccessLogView::render('logs.ip', [
            'ip' => $ip,
            'summary' => $summary,
            'logs' => $recent,
            'firewallAvailable' => $this->logs->firewallAvailable(),
        ]);
    }

    public function block(Request $request, string $ip): RedirectResponse
    {
        if (! $this->logs->firewallAvailable()) {
            return back()->withErrors(['firewall' => mca_alog('errors.firewall_missing')]);
        }

        $reason = trim((string) $request->input('reason', mca_alog('actions.block_reason_default')));

        mca_firewall()->blacklist($ip, [
            'label' => mca_alog('actions.block_label'),
            'reason' => $reason !== '' ? $reason : null,
            'is_active' => true,
        ], $request->user()?->getAuthIdentifier());

        $np = config('access-log.routes.web.name_prefix', 'mca.access-log.');

        return redirect()
            ->route($np.'ip', ['ip' => $ip])
            ->with('mca_alog_status', mca_alog('flash.blocked', ['ip' => $ip]));
    }

    public function whitelist(Request $request, string $ip): RedirectResponse
    {
        if (! $this->logs->firewallAvailable()) {
            return back()->withErrors(['firewall' => mca_alog('errors.firewall_missing')]);
        }

        mca_firewall()->whitelist($ip, [
            'label' => mca_alog('actions.whitelist_label'),
            'reason' => trim((string) $request->input('reason', '')) ?: null,
            'is_active' => true,
        ], $request->user()?->getAuthIdentifier());

        $np = config('access-log.routes.web.name_prefix', 'mca.access-log.');

        return redirect()
            ->route($np.'ip', ['ip' => $ip])
            ->with('mca_alog_status', mca_alog('flash.whitelisted', ['ip' => $ip]));
    }
}
