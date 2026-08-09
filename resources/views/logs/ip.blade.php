@php
    $np = config('access-log.routes.web.name_prefix', 'mca.access-log.');
@endphp
@extends(\Mca\AccessLog\Support\McaAccessLogView::layout())

@section('title', mca_alog('pages.ip_title', ['ip' => $ip]))

@section('content')
    <div class="mca-alog-toolbar">
        <div>
            <h1 class="mca-perm-title">{{ mca_alog('pages.ip_title', ['ip' => $ip]) }}</h1>
            <p class="mca-perm-help">
                {{ mca_alog('fields.hits') }}: {{ number_format($summary['hits']) }} ·
                {{ mca_alog('fields.blocked') }}: {{ number_format($summary['blocked']) }} ·
                {{ mca_alog('fields.unique_paths') }}: {{ number_format($summary['unique_paths']) }}
                @if ($summary['last_seen'])
                    · {{ mca_alog('fields.last_seen') }}: {{ $summary['last_seen']->diffForHumans() }}
                @endif
            </p>
        </div>
        <div class="mca-alog-toolbar__actions">
            <a href="{{ route($np.'index', ['ip' => $ip]) }}" class="mca-ui-btn mca-ui-btn--ghost">{{ mca_alog('nav.logs') }}</a>
            @if ($firewallAvailable)
                <form method="post" action="{{ route($np.'block', ['ip' => $ip]) }}" data-mca-confirm="{{ mca_alog('confirm.block') }}">
                    @csrf
                    <button type="submit" class="mca-ui-btn mca-ui-btn--danger">{{ mca_alog('actions.block') }}</button>
                </form>
                <form method="post" action="{{ route($np.'whitelist', ['ip' => $ip]) }}" data-mca-confirm="{{ mca_alog('confirm.whitelist') }}">
                    @csrf
                    <button type="submit" class="mca-ui-btn mca-ui-btn--primary">{{ mca_alog('actions.whitelist') }}</button>
                </form>
                @if (Route::has('mca.firewall.index'))
                    <a href="{{ route('mca.firewall.index', ['q' => $ip]) }}" class="mca-ui-btn mca-ui-btn--ghost">{{ mca_alog('actions.open_firewall') }}</a>
                @endif
            @endif
        </div>
    </div>

    <div class="mca-alog-stats">
        <div class="mca-perm-card">
            <div class="mca-perm-card__header">{{ mca_alog('fields.method') }}</div>
            <div class="mca-perm-card__body">
                @forelse ($summary['methods'] as $method => $count)
                    <div class="mca-alog-stat-row"><span>{{ $method }}</span><strong>{{ number_format($count) }}</strong></div>
                @empty
                    <p class="mca-perm-help">—</p>
                @endforelse
            </div>
        </div>
        <div class="mca-perm-card">
            <div class="mca-perm-card__header">{{ mca_alog('fields.status') }}</div>
            <div class="mca-perm-card__body">
                @forelse ($summary['statuses'] as $code => $count)
                    <div class="mca-alog-stat-row"><span>{{ $code }}</span><strong>{{ number_format($count) }}</strong></div>
                @empty
                    <p class="mca-perm-help">—</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mca-perm-card">
        <div class="mca-perm-card__body mca-alog-table-wrap">
            @if ($logs->isEmpty())
                <p class="mca-perm-help">{{ mca_alog('table.empty') }}</p>
            @else
                <table class="mca-alog-table">
                    <thead>
                        <tr>
                            <th>{{ mca_alog('fields.when') }}</th>
                            <th>{{ mca_alog('fields.method') }}</th>
                            <th>{{ mca_alog('fields.path') }}</th>
                            <th>{{ mca_alog('fields.status') }}</th>
                            <th>{{ mca_alog('fields.duration') }}</th>
                            <th>{{ mca_alog('fields.user_agent') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr class="{{ $log->is_blocked ? 'is-blocked' : '' }}">
                                <td>{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $log->method }}</td>
                                <td class="mca-alog-path">{{ $log->path }}</td>
                                <td>{{ $log->status_code }}</td>
                                <td>{{ $log->duration_ms !== null ? $log->duration_ms.' ms' : '—' }}</td>
                                <td class="mca-alog-ua" title="{{ $log->user_agent }}">{{ \Illuminate\Support\Str::limit($log->user_agent, 40) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mca-alog-pagination">{{ $logs->links() }}</div>
            @endif
        </div>
    </div>
@endsection
