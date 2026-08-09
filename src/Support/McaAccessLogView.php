<?php

namespace Mca\AccessLog\Support;

use Illuminate\Contracts\View\View;

final class McaAccessLogView
{
    public static function layout(): string
    {
        return (string) config('access-log.views.layout', 'mca-access-log::layouts.app');
    }

    public static function render(string $view, array $data = []): View
    {
        McaAccessLogLocale::apply();

        $namespace = config('access-log.views.namespace', 'mca-access-log');

        return view($namespace.'::'.$view, array_merge([
            'mcaAlogTitle' => config('access-log.ui.title') ?: mca_alog('app.title'),
        ], $data));
    }

    public static function uiCssUrl(): string
    {
        return asset((string) config('access-log.ui.assets.ui', 'vendor/mca-permission/mca-ui.css'));
    }

    public static function uiJsUrl(): string
    {
        return asset((string) config('access-log.ui.assets.ui_js', 'vendor/mca-permission/mca-ui.js'));
    }

    public static function cssUrl(): string
    {
        return asset((string) config('access-log.ui.assets.css', 'vendor/mca-access-log/mca-access-log.css'));
    }

    public static function jsUrl(): string
    {
        return asset((string) config('access-log.ui.assets.js', 'vendor/mca-access-log/mca-access-log.js'));
    }
}
