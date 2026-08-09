<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $mcaAlogTitle ?? mca_alog('app.title'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ \Mca\AccessLog\Support\McaAccessLogView::uiCssUrl() }}">
    <link rel="stylesheet" href="{{ \Mca\AccessLog\Support\McaAccessLogView::cssUrl() }}">
    @stack('mca-alog-head')
</head>
<body class="mca-ui-root mca-perm-root mca-alog-root">
    @include('mca-access-log::partials.header')

    <main class="mca-ui-main mca-perm-main mca-alog-main">
        @include('mca-access-log::partials.flash')
        @yield('content')
    </main>

    @php
        $mcaUiI18n = [
            'ok' => mca_alog('modal.ok'),
            'confirm' => mca_alog('modal.confirm'),
            'cancel' => mca_alog('modal.cancel'),
            'close' => mca_alog('modal.close'),
            'alert_title' => mca_alog('modal.alert_title'),
            'confirm_title' => mca_alog('modal.confirm_title'),
        ];
    @endphp
    <script>
        window.McaUiI18n = @json($mcaUiI18n);
    </script>
    <script src="{{ \Mca\AccessLog\Support\McaAccessLogView::uiJsUrl() }}" defer></script>
    <script src="{{ \Mca\AccessLog\Support\McaAccessLogView::jsUrl() }}" defer></script>
    @stack('mca-alog-scripts')
</body>
</html>
