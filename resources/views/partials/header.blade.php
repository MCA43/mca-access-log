@php
    $np = config('access-log.routes.web.name_prefix', 'mca.access-log.');
@endphp
<header class="mca-ui-shell" id="mcaUiShell">
    <div class="mca-ui-shell__wrap">
        <div class="mca-ui-shell__inner">
            <a href="{{ route($np.'index') }}" class="mca-ui-brand">
                <span class="mca-ui-brand__mark" aria-hidden="true">
                    @include('mca-access-log::partials.icon', ['name' => 'list'])
                </span>
                <span>{{ $mcaAlogTitle ?? mca_alog('app.brand') }}</span>
            </a>

            <button type="button"
                    class="mca-ui-menu-btn"
                    id="mcaUiMenuBtn"
                    aria-expanded="false"
                    aria-controls="mcaUiNav"
                    aria-label="{{ mca_alog('app.nav_aria') }}">
                @include('mca-access-log::partials.icon', ['name' => 'menu'])
            </button>
        </div>

        <nav class="mca-ui-nav" id="mcaUiNav" aria-label="{{ mca_alog('app.nav_aria') }}">
            @if(Route::has('mca.hub.index'))
                <a href="{{ route('mca.hub.index') }}" class="mca-ui-nav__link">
                    @include('mca-access-log::partials.icon', ['name' => 'grid', 'class' => 'mca-ui-icon mca-ui-icon--sm'])
                    {{ mca_alog('nav.back_mca') }}
                </a>
            @endif

            <a href="{{ route($np.'index') }}"
               class="mca-ui-nav__link {{ request()->routeIs($np.'*') ? 'mca-ui-nav__link--active' : '' }}">
                {{ mca_alog('nav.logs') }}
            </a>

            @if(Route::has('mca.firewall.index'))
                <a href="{{ route('mca.firewall.index') }}" class="mca-ui-nav__link">
                    {{ mca_alog('nav.firewall') }}
                </a>
            @endif
        </nav>
    </div>
</header>
