@php
    $np = config('access-log.routes.web.name_prefix', 'mca.access-log.');
    $f = $filters;
@endphp
@extends(\Mca\AccessLog\Support\McaAccessLogView::layout())

@section('title', mca_alog('pages.index_title'))

@section('content')
    <div class="mca-alog-toolbar">
        <div>
            <h1 class="mca-perm-title">{{ mca_alog('pages.index_title') }}</h1>
            <p class="mca-perm-help">
                {{ number_format($counts['total']) }} ·
                {{ mca_alog('table.blocked_badge') }}: {{ number_format($counts['blocked']) }}
            </p>
        </div>
    </div>

    <div class="mca-alog-layout">
        <aside class="mca-perm-card mca-alog-side">
            <div class="mca-perm-card__header">{{ mca_alog('table.top_ips') }}</div>
            <div class="mca-perm-card__body">
                @forelse ($topIps as $row)
                    <a href="{{ route($np.'ip', ['ip' => $row->ip]) }}" class="mca-alog-side__row">
                        <code>{{ $row->ip }}</code>
                        <span>{{ number_format($row->hits) }}</span>
                    </a>
                @empty
                    <p class="mca-perm-help">{{ mca_alog('table.empty') }}</p>
                @endforelse
            </div>
        </aside>

        <div class="mca-alog-maincol">
            <form method="get" action="{{ route($np.'index') }}" class="mca-perm-card mca-alog-filters">
                <div class="mca-perm-card__body mca-alog-filters__grid">
                    <label>
                        <span class="mca-perm-label">{{ mca_alog('filters.ip') }}</span>
                        <input type="text" name="ip" value="{{ $f['ip'] }}" class="mca-perm-input">
                    </label>
                    <label>
                        <span class="mca-perm-label">{{ mca_alog('filters.method') }}</span>
                        <input type="text" name="method" value="{{ $f['method'] }}" class="mca-perm-input" placeholder="GET">
                    </label>
                    <label>
                        <span class="mca-perm-label">{{ mca_alog('filters.status') }}</span>
                        <input type="text" name="status" value="{{ $f['status'] }}" class="mca-perm-input" placeholder="403">
                    </label>
                    <label>
                        <span class="mca-perm-label">{{ mca_alog('filters.blocked') }}</span>
                        <select name="blocked" class="mca-perm-input">
                            <option value="">{{ mca_alog('filters.blocked_all') }}</option>
                            <option value="1" @selected($f['blocked'] === '1')>{{ mca_alog('filters.blocked_yes') }}</option>
                            <option value="0" @selected($f['blocked'] === '0')>{{ mca_alog('filters.blocked_no') }}</option>
                        </select>
                    </label>
                    <label class="mca-alog-filters__wide">
                        <span class="mca-perm-label">{{ mca_alog('filters.q') }}</span>
                        <input type="search" name="q" value="{{ $f['q'] }}" class="mca-perm-input">
                    </label>
                    <div class="mca-alog-filters__actions">
                        <button type="submit" class="mca-ui-btn mca-ui-btn--primary mca-ui-btn--sm">{{ mca_alog('filters.apply') }}</button>
                        <a href="{{ route($np.'index') }}" class="mca-ui-btn mca-ui-btn--ghost mca-ui-btn--sm">{{ mca_alog('filters.clear') }}</a>
                    </div>
                </div>
            </form>

            <div class="mca-perm-card">
                <div class="mca-perm-card__body mca-alog-table-wrap">
                    @if ($logs->isEmpty())
                        <p class="mca-perm-help">{{ mca_alog('table.empty') }}</p>
                    @else
                        <table class="mca-alog-table">
                            <thead>
                                <tr>
                                    <th>{{ mca_alog('fields.when') }}</th>
                                    <th>{{ mca_alog('fields.ip') }}</th>
                                    <th>{{ mca_alog('fields.method') }}</th>
                                    <th>{{ mca_alog('fields.path') }}</th>
                                    <th>{{ mca_alog('fields.status') }}</th>
                                    <th>{{ mca_alog('fields.duration') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr class="{{ $log->is_blocked ? 'is-blocked' : '' }}">
                                        <td>{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route($np.'ip', ['ip' => $log->ip]) }}"><code>{{ $log->ip }}</code></a>
                                            @if ($log->is_blocked)
                                                <span class="mca-alog-badge">{{ mca_alog('table.blocked_badge') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->method }}</td>
                                        <td class="mca-alog-path" title="{{ $log->path }}">{{ \Illuminate\Support\Str::limit($log->path, 60) }}</td>
                                        <td>{{ $log->status_code }}</td>
                                        <td>{{ $log->duration_ms !== null ? $log->duration_ms.' ms' : '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mca-alog-pagination">{{ $logs->links('mca-access-log::partials.pagination') }}</div>
                    @endif
                </div>
            </div>

            <p class="mca-perm-help">{{ mca_alog('hint.suite') }} · {{ mca_alog('hint.retention') }}</p>
        </div>
    </div>
@endsection
